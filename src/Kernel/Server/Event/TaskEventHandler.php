<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Event;

use Nyxio\Contract\Container\ContainerInterface;
use Nyxio\Contract\Event\EventDispatcherInterface;
use Nyxio\Contract\Kernel\Server\Job\OptionsInterface;
use Nyxio\Kernel\Event\QueueException;
use Nyxio\Kernel\Event\ScheduleException;
use Nyxio\Kernel\Server\Job\JobType;
use Nyxio\Kernel\Server\Job\TaskData;
use Swoole\Http\Server;

/**
 * @codeCoverageIgnore
 */
class TaskEventHandler
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function handle(Server $server, int $taskId, int $reactorId, TaskData $taskData): void
    {
        try {
            $this->invoke($server, $taskData);
        } catch (\Throwable $exception) {
            switch ($taskData->type) {
                case JobType::Queue:
                    $this->eventDispatcher->dispatch(
                        QueueException::NAME,
                        new QueueException($taskData, $exception)
                    );
                    break;
                case  JobType::Scheduled:
                    $this->eventDispatcher->dispatch(
                        ScheduleException::NAME,
                        new ScheduleException($taskData, $exception)
                    );
                    break;
            }
        }
    }

    /**
     * @param Server $server
     * @param TaskData $taskData
     * @return void
     * @throws \Throwable
     */
    private function invoke(Server $server, TaskData $taskData): void
    {
        try {
            $job = $this->container->get($taskData->job);

            $reflection = new \ReflectionClass($taskData->job);

            if (!$reflection->hasMethod('handle')) {
                throw new \RuntimeException(
                    \sprintf("Job %s (%s) doesn't have `handle` method", $taskData->job, $taskData->uuid)
                );
            }

            $handle = $reflection->getMethod('handle');

            if (($taskData->options instanceof OptionsInterface) && $taskData->options->getDelay() !== null) {
                $server->after(
                    $taskData->options->getDelay(),
                    static function () use ($job, $taskData, $handle, $server) {
                        $handle->invokeArgs($job, $taskData->data);
                        $server->finish($taskData);
                    }
                );

                return;
            }

            $handle->invokeArgs($job, $taskData->data);
            $server->finish($taskData);
        } catch (\Throwable $exception) {
            if (
                ($taskData->options instanceof OptionsInterface)
                && $taskData->options->getRetryCount() !== null
                && $taskData->options->getRetryCount() > 0
            ) {
                $taskData->options->decreaseRetryCount();

                if ($taskData->options->getRetryDelay() !== null) {
                    $server->after($taskData->options->getRetryDelay(), function () use ($server, $taskData) {
                        $this->invoke($server, $taskData);
                    });

                    return;
                }

                $this->invoke($server, $taskData);

                return;
            }

            throw $exception;
        }
    }
}

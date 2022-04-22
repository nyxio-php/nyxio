<?php

declare(strict_types=1);

namespace Nyxio\Kernel\Server\Job\Queue;

use Nyxio\Contract\Kernel\Server\Job\OptionsInterface;
use Nyxio\Contract\Kernel\Server\Job\Queue\QueueInterface;
use Nyxio\Kernel\Server\Job\JobType;
use Nyxio\Kernel\Server\Job\TaskData;
use Ramsey\Uuid\Uuid;

class Queue implements QueueInterface
{
    /**
     * @var TaskData[]
     */
    private array $queue = [];


    public function push(string $job, array $data = [], ?OptionsInterface $options = null): void
    {
        $this->queue[Uuid::uuid4()->toString()] = new TaskData(
            job:     $job,
            uuid:    Uuid::uuid4()->toString(),
            type:    JobType::Queue,
            data:    $data,
            options: $options
        );
    }

    /**
     * @return TaskData[]
     */
    public function getQueue(): array
    {
        return $this->queue;
    }

    public function complete(string $uuid): void
    {
        if (isset($this->queue[$uuid])) {
            unset($this->queue[$uuid]);
        }
    }
}

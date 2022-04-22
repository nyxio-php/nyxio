<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Event;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Nyxio\Kernel\Event\ScheduleComplete;
use Nyxio\Kernel\Event\ScheduleException;
use Nyxio\Kernel\Event\QueueComplete;
use Nyxio\Kernel\Event\QueueException;
use Nyxio\Kernel\Event\ResponseEvent;
use Nyxio\Kernel\Server\Job\JobType;
use Nyxio\Kernel\Server\Job\TaskData;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testBasic(): void
    {
        $taskData = new TaskData('test.job', uuid: 'test', type: JobType::Queue);
        $event = new ScheduleComplete($taskData);
        $this->assertEquals('kernel.job.schedule.complete', $event::NAME);
        $this->assertEquals('test', $event->taskData->uuid);

        $event = new ScheduleException($taskData, new \Exception('exception message'));
        $this->assertEquals('kernel.job.schedule.exception', $event::NAME);
        $this->assertEquals('test', $event->taskData->uuid);
        $this->assertEquals('exception message', $event->exception->getMessage());

        $event = new QueueComplete($taskData);
        $this->assertEquals('kernel.job.queue.complete', $event::NAME);
        $this->assertEquals('test', $event->taskData->uuid);

        $event = new QueueException($taskData, new \Exception('exception message'));
        $this->assertEquals('kernel.job.queue.exception', $event::NAME);
        $this->assertEquals('test', $event->taskData->uuid);
        $this->assertEquals('exception message', $event->exception->getMessage());

        $event = new ResponseEvent(new Response(200), new ServerRequest('GET', '/'));
        $this->assertEquals('kernel.response', $event::NAME);
        $this->assertEquals('GET', $event->request->getMethod());
        $this->assertEquals('/', $event->request->getUri());
        $this->assertEquals(200, $event->response->getStatusCode());
    }
}

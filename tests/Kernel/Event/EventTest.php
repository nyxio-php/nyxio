<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Event;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use Nyxio\Kernel\Event\CronJobCompleted;
use Nyxio\Kernel\Event\CronJobError;
use Nyxio\Kernel\Event\JobCompleted;
use Nyxio\Kernel\Event\JobError;
use Nyxio\Kernel\Event\ResponseEvent;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testBasic(): void
    {
        $event = new CronJobCompleted('cron.job');
        $this->assertEquals('kernel.cron.completed', $event::NAME);
        $this->assertEquals('cron.job', $event->job);

        $event = new CronJobError('cron.job', new \Exception('exception message'));
        $this->assertEquals('kernel.cron.error', $event::NAME);
        $this->assertEquals('cron.job', $event->job);
        $this->assertEquals('exception message', $event->exception->getMessage());

        $event = new JobCompleted('app.job');
        $this->assertEquals('kernel.job.completed', $event::NAME);
        $this->assertEquals('app.job', $event->job);

        $event = new JobError('app.job', new \Exception('exception message'));
        $this->assertEquals('kernel.job.error', $event::NAME);
        $this->assertEquals('app.job', $event->job);
        $this->assertEquals('exception message', $event->exception->getMessage());

        $event = new ResponseEvent(new Response(200), new ServerRequest('GET', '/'));
        $this->assertEquals('kernel.response', $event::NAME);
        $this->assertEquals('GET', $event->request->getMethod());
        $this->assertEquals('/', $event->request->getUri());
        $this->assertEquals(200, $event->response->getStatusCode());
    }
}

<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job;

use Nyxio\Container\Container;
use Nyxio\Kernel\Server\Job\BaseTaskHandler;
use Nyxio\Kernel\Server\Job\TaskData;
use Nyxio\Kernel\Server\Job\TaskType;
use Nyxio\Tests\Kernel\Server\Job\Fixtures\TestJob;
use PHPUnit\Framework\TestCase;

class BaseTaskHandlerTest extends TestCase
{
    public function testBasic(): void
    {
        $container = new Container();
        $handler = new class ($container) extends BaseTaskHandler {
            public function test(string $message): string
            {
                return $this->invokeJob(
                    new TaskData(
                        job: TestJob::class,
                        uuid: 'test',
                        type: TaskType::Await,
                        data: ['message' => $message],
                    )
                );
            }
        };

        $this->assertEquals('TEST MESSAGE', $handler->test('TEST MESSAGE'));
    }
}

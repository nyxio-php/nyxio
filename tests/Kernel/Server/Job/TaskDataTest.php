<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job;

use Nyxio\Kernel\Server\Job\Async\Options;
use Nyxio\Kernel\Server\Job\TaskData;
use Nyxio\Kernel\Server\Job\TaskType;
use PHPUnit\Framework\TestCase;

class TaskDataTest extends TestCase
{
    public function testBasic(): void
    {
        $data = new TaskData(
            job:     'test job',
            uuid:    'uuid',
            type:    TaskType::Queue,
            data:    ['data' => 'value'],
            options: new Options(delay: 10)
        );

        $this->assertEquals('test job', $data->job);
        $this->assertEquals(['data' => 'value'], $data->data);
        $this->assertEquals(10, $data->options->getDelay());
        $this->assertEquals(TaskType::Queue, $data->type);
        $this->assertEquals('uuid', $data->uuid);
        $this->assertEquals(true, $data->isAsync());
    }

    public function testNotAsync(): void
    {
        $data = new TaskData(
            job:     'test job',
            uuid:    'uuid',
            type:    TaskType::Await,
            data:    ['data' => 'value'],
            options: new Options(delay: 10)
        );

        $this->assertEquals('test job', $data->job);
        $this->assertEquals(['data' => 'value'], $data->data);
        $this->assertEquals(10, $data->options->getDelay());
        $this->assertEquals(TaskType::Await, $data->type);
        $this->assertEquals('uuid', $data->uuid);
        $this->assertEquals(false, $data->isAsync());
    }
}

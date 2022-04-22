<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server\Job;

use Nyxio\Kernel\Server\Job\JobType;
use Nyxio\Kernel\Server\Job\Options;
use Nyxio\Kernel\Server\Job\TaskData;
use PHPUnit\Framework\TestCase;

class TaskDataTest extends TestCase
{
    public function testBasic(): void
    {
        $data = new TaskData(
            job:     'test job',
            uuid:    'uuid',
            type:    JobType::Queue,
            data:    ['data' => 'value'],
            options: new Options(delay: 10)
        );

        $this->assertEquals('test job', $data->job);
        $this->assertEquals(['data' => 'value'], $data->data);
        $this->assertEquals(10, $data->options->getDelay());
        $this->assertEquals(JobType::Queue, $data->type);
        $this->assertEquals('uuid', $data->uuid);
    }
}

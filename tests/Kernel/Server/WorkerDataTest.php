<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Server;

use Nyxio\Kernel\Server\Queue\Options;
use Nyxio\Kernel\Server\WorkerData;
use PHPUnit\Framework\TestCase;

class WorkerDataTest extends TestCase
{
    public function testBasic(): void
    {
        $data = new WorkerData(
            job:       'test job',
            data:      ['data' => 'value'],
            options:   new Options(delay: 10),
            isCronJob: true,
        );

        $this->assertEquals('test job', $data->job);
        $this->assertEquals(['data' => 'value'], $data->data);
        $this->assertEquals(10, $data->options->getDelay());
        $this->assertTrue($data->isCronJob);
    }
}

<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Env;

use PHPUnit\Framework\TestCase;

use function Nyxio\Helper\Env\env;

class EnvTest extends TestCase
{
    public function testBasic(): void
    {
        $this->assertEquals(1, env('TEST_INTEGER'));
        $this->assertEquals(1.1, env('TEST_FLOAT'));
        $this->assertEquals('secret', env('TEST_STRING'));
        $this->assertEquals(false, env('TEST_BOOLEAN_FALSE'));
        $this->assertEquals(true, env('TEST_BOOLEAN_TRUE'));

        $this->assertEquals(null, env('TEST_INVALID_ENV'));
    }
}

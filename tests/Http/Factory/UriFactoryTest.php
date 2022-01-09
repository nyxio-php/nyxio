<?php

declare(strict_types=1);

namespace Nyxio\Tests\Http\Factory;

use Nyxio\Http\Factory\UriFactory;
use PHPUnit\Framework\TestCase;

class UriFactoryTest extends TestCase
{
    public function testBasic(): void
    {
        $factory = new UriFactory();

        $this->assertEquals('/test', $factory->createUri('/test/')->getPath());
    }
}

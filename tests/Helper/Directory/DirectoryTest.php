<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Directory;

use PHPUnit\Framework\TestCase;

use function Nyxio\Helper\Directory\join;

class DirectoryTest extends TestCase
{
    public function testBasic(): void
    {
        $this->assertEquals('test/page/joined', join('test', 'page', 'joined'));
        $this->assertEquals('/test/page/joined', join('/test/page', 'joined'));
        $this->assertEquals('/test/page/joined/file.xml', join('/test/page/', '/joined', 'file.xml'));
    }
}

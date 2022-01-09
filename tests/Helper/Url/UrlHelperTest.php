<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Url;

use PHPUnit\Framework\TestCase;

use function Nyxio\Helper\Url\normalizeUri;

class UrlHelperTest extends TestCase
{
    /**
     * @param string $value
     * @param string $excepted
     * @return void
     *
     * @dataProvider getTestDataForNormalizeUri
     */
    public function testNormalizeUri(string $value, string $excepted): void
    {
        $this->assertEquals(
            $excepted,
            normalizeUri($value)
        );
    }

    public function getTestDataForNormalizeUri(): \Generator
    {
        yield ['value' => 'test', 'excepted' => '/test'];
        yield ['value' => 'test/', 'excepted' => '/test'];
        yield ['value' => '/test', 'excepted' => '/test'];
        yield ['value' => '/', 'excepted' => '/'];
        yield ['value' => '/  ', 'excepted' => '/'];
        yield ['value' => '  /test  ', 'excepted' => '/test'];
        yield ['value' => '/test/', 'excepted' => '/test'];
        yield ['value' => 'test/uri/user/', 'excepted' => '/test/uri/user'];
    }
}

<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Url;

use PHPUnit\Framework\TestCase;

use function Nyxio\Helper\Url\joinUri;
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

    /**
     * @param string $excepted
     * @param ...$uri
     * @return void
     *
     * @dataProvider getTestDataForJoinUri
     */
    public function testJoinUri(string $excepted, string ...$uri): void
    {
        $this->assertEquals(
            $excepted,
            joinUri(...$uri)
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

    public function getTestDataForJoinUri(): \Generator
    {
        yield ['value' => '/test/joined/uri', '/test', 'joined', '/uri/'];
        yield ['value' => '/', ''];
        yield ['value' => '/test/url/test/test/test', '/test/', 'url', '/test/', '/test', 'test/'];
        yield ['value' => '/test/url/test/test/test', '/test/', 'url', '/test/', '/test', 'test/'];
    }
}

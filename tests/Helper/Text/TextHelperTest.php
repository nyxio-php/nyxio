<?php

declare(strict_types=1);

namespace Nyxio\Tests\Helper\Text;

use PHPUnit\Framework\TestCase;

use function Nyxio\Helper\Text\parseFromString;

class TextHelperTest extends TestCase
{
    /**
     * @param string $value
     * @param mixed $excepted
     * @return void
     *
     * @dataProvider getTestDataForParseFromString
     */
    public function testParseFromString(string $value, mixed $excepted): void
    {
        $this->assertEquals(
            $excepted,
            parseFromString($value)
        );
    }

    public function getTestDataForParseFromString(): \Generator
    {
        yield ['value' => '1234', 'excepted' => 1234];
        yield ['value' => 'NYXIO TEST', 'excepted' => 'NYXIO TEST'];
        yield ['value' => '12.24', 'excepted' => 12.24];
        yield ['value' => '12.24.11', 'excepted' => '12.24.11'];
        yield ['value' => '.11', 'excepted' => 0.11];
        yield ['value' => '1984', 'excepted' => 1984];
        yield ['value' => 'true', 'excepted' => true];
        yield ['value' => 'True', 'excepted' => true];
        yield ['value' => 'TRUE', 'excepted' => true];
        yield ['value' => 'FALSE', 'excepted' => false];
        yield ['value' => 'FaLsE', 'excepted' => false];
        yield ['value' => 'false', 'excepted' => false];
        yield ['value' => 'null', 'excepted' => null];
        yield ['value' => 'Null', 'excepted' => null];
        yield ['value' => 'nUll', 'excepted' => null];
        yield ['value' => 'NULL', 'excepted' => null];
    }
}

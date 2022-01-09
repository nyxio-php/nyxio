<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Exception;

use Nyxio\Contract\Http\HttpStatus;
use Nyxio\Http\Exception\HttpException;
use Nyxio\Kernel\Exception\Transformer\ExceptionTransformer;
use PHPUnit\Framework\TestCase;

class ExceptionTransformerTest extends TestCase
{
    public function testException(): void
    {
        $transformer = new ExceptionTransformer();

        $this->assertEquals(
            [
                'code' => 500,
                'message' => 'Internal Server Error',
            ],
            $transformer->toArray(new \InvalidArgumentException('Random exception'))
        );
    }

    public function testHttpException(): void
    {
        $transformer = new ExceptionTransformer();

        $this->assertEquals(
            [
                'code' => 404,
                'message' => 'Page Not Found (transformer)',
            ],
            $transformer->toArray(new HttpException(HttpStatus::PageNotFound, 'Page Not Found (transformer)'))
        );
    }

    public function testValidationException(): void
    {
        $transformer = new ExceptionTransformer();

        $this->assertEquals(
            [
                'code' => 400,
                'message' => 'Bad Request',
                'errors' => [
                    'test' => 'error'
                ]
            ],
            $transformer->toArray(new HttpException(HttpStatus::BadRequest, 'Bad Request', errors: ['test' => 'error']))
        );
    }

    public function testExceptionWithDebugMode(): void
    {
        $transformer = new ExceptionTransformer(debug: true);

        $result = $transformer->toArray(new \InvalidArgumentException('Random exception'));

        $this->assertEquals(0, $result['code']);
        $this->assertEquals('Random exception', $result['message']);
        $this->assertEquals(__FILE__, $result['file']);
        $this->assertNotEmpty($result['trace']);
        $this->assertNotEmpty($result['line']);
        $this->assertNull($result['previous']);
    }

    public function testExceptionPreviousException(): void
    {
        $transformer = new ExceptionTransformer();
        $transformer->setDebug(true);

        $result = $transformer->toArray(
            new \InvalidArgumentException('Random exception', previous: new \Exception('TEST'))
        );

        $this->assertEquals('TEST', $result['previous']['message']);
        $this->assertEquals(__FILE__, $result['previous']['file']);
        $this->assertNotEmpty($result['previous']['trace']);
        $this->assertNotEmpty($result['previous']['line']);
        $this->assertNull($result['previous']['previous']);
    }
}

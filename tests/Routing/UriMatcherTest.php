<?php

declare(strict_types=1);

namespace Nyxio\Tests\Routing;

use Nyholm\Psr7\ServerRequest;
use Nyxio\Container\Container;
use Nyxio\Contract\Http\Method;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Routing\Attribute\Route;
use Nyxio\Routing\UriMatcher;
use Nyxio\Validation\DefaultRules;
use Nyxio\Validation\Handler\RulesChecker;
use Nyxio\Validation\RuleExecutorCollection;
use PHPUnit\Framework\TestCase;

class UriMatcherTest extends TestCase
{
    /**
     * @return void
     */
    public function testSimple(): void
    {
        $matcher = $this->createMatcherInstance();

        $route = new Route(Method::GET, '/test');
        $request = new ServerRequest(Method::GET->value, '/test');

        $this->assertTrue($matcher->compare($request, $route));
        $this->assertEmpty($matcher->getQueryParams());
    }

    /**
     * @return void
     */
    public function testComplexity(): void
    {
        $matcher = $this->createMatcherInstance();

        $route = new Route(Method::GET, '/api/v1/user/@userId/order/@orderId');
        $request = new ServerRequest(Method::GET->value, '/api/v1/user/49291/order/1984');

        $this->assertTrue($matcher->compare($request, $route));
        $this->assertEquals(
            [
                'userId' => 49291,
                'orderId' => 1984,
            ],
            $matcher->getQueryParams()
        );
    }

    /**
     * @param string $routeUri
     * @param string $requestUri
     * @param array $rules
     * @param bool $excepted
     * @return void
     * @dataProvider getValidationDataProvider
     */
    public function testWithValidation(string $routeUri, string $requestUri, array $rules, bool $excepted): void
    {
        $matcher = $this->createMatcherInstance();

        $route = new Route(method: Method::GET, uri: $routeUri, rules: $rules);
        $request = new ServerRequest(Method::GET->value, $requestUri);

        $this->assertEquals($excepted, $matcher->compare($request, $route));
    }

    /**
     * @param string $routeUri
     * @param string $requestUri
     * @return void
     * @dataProvider getNotMatchDataProvider
     */
    public function testNotMatch(string $routeUri, string $requestUri): void
    {
        $matcher = $this->createMatcherInstance();

        $route = new Route(Method::GET, $routeUri);
        $request = new ServerRequest(Method::GET->value, $requestUri);

        $this->assertFalse($matcher->compare($request, $route));
    }

    private function getNotMatchDataProvider(): \Generator
    {
        yield ['routeUri' => '/', 'requestUri' => '/test'];
        yield ['routeUri' => '/', 'requestUri' => '/test/test'];
        yield ['routeUri' => '/test/test/test', 'requestUri' => '/test/test'];
        yield ['routeUri' => '/test/foo/bar', 'requestUri' => '/test/bar/foo'];
    }

    private function getValidationDataProvider(): \Generator
    {
        yield [
            'routeUri' => '/user/@userId',
            'requestUri' => '/user/1',
            'rules' => [
                'userId' => 'integer',
            ],
            'excepted' => true,
        ];

        yield [
            'routeUri' => '/user/@userId',
            'requestUri' => '/user/1',
            'rules' => [
                'userId' => 'string',
            ],
            'excepted' => false,
        ];

        yield [
            'routeUri' => '/user/@userId',
            'requestUri' => '/user/test-user-id',
            'rules' => [
                'userId' => 'string',
            ],
            'excepted' => true,
        ];

        yield [
            'routeUri' => '/user/@userId',
            'requestUri' => '/user/test-user-id',
            'rules' => [
                'userId' => ['string', 'max-len' => ['max' => 2]],
            ],
            'excepted' => false,
        ];

        yield [
            'routeUri' => '/user/@userId',
            'requestUri' => '/user/test-user-id',
            'rules' => [
                'userId' => ['string', 'max-len' => ['max' => 20]],
            ],
            'excepted' => true,
        ];
    }

    private function createMatcherInstance(): UriMatcher
    {
        $collection = new RuleExecutorCollection(new Container(), new ExtractAttribute());
        $collection->register(DefaultRules::class);

        return new UriMatcher(new RulesChecker($collection));
    }
}

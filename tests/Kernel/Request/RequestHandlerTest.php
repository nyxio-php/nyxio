<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Request;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Helper\Attribute\ExtractAttribute;
use Nyxio\Http\Factory\RequestFactory;
use Nyxio\Http\Factory\ResponseFactory;
use Nyxio\Http\Factory\UriFactory;
use Nyxio\Kernel\Exception\Transformer\ExceptionTransformer;
use Nyxio\Kernel\Request\ActionCollection;
use Nyxio\Kernel\Request\RequestHandler;
use Nyxio\Kernel\Text\Message;
use Nyxio\Routing\Group;
use Nyxio\Routing\GroupCollection;
use Nyxio\Routing\UriMatcher;
use Nyxio\Tests\Kernel\Request\Fixture\ActionWithInvalidMiddleware;
use Nyxio\Tests\Kernel\Request\Fixture\TestAction;
use Nyxio\Tests\Kernel\Request\Fixture\TestActionWithQuery;
use Nyxio\Validation\RuleExecutorCollection;
use Nyxio\Validation\RulesChecker;
use PHPUnit\Framework\TestCase;

class RequestHandlerTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testBasic(): void
    {
        $container = new Container();
        $config = new MemoryConfig();
        $extractAttribute = new ExtractAttribute();
        $groupCollection = (new GroupCollection())->register(new Group('version1', prefix: '/api/v1'));
        $actionCollection = new ActionCollection($container, $extractAttribute, $groupCollection);
        $actionCollection->create([TestAction::class]);

        $handler = new RequestHandler(
            matcher:              new UriMatcher(
                                      new RulesChecker(new RuleExecutorCollection($container, $extractAttribute), new Message($config))
                                  ),
            container:            $container,
            exceptionTransformer: new ExceptionTransformer(),
            responseFactory:      new ResponseFactory(),
            actionCollection:     $actionCollection,
        );

        $request = (new RequestFactory(new UriFactory()))->createServerRequest('POST', '/api/v1/user/4');

        $response = $handler->handle($request);

        $this->assertEquals('"test"', (string)$response->getBody());
        $this->assertEquals(201, $response->getStatusCode());

        $request = (new RequestFactory(new UriFactory()))->createServerRequest('POST', 'unknown page');

        $response = $handler->handle($request);

        $this->assertEquals('{"code":404,"message":"Page Not Found"}', (string)$response->getBody());
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testInvalidMiddlewares(): void
    {
        $container = new Container();
        $config = new MemoryConfig();
        $extractAttribute = new ExtractAttribute();
        $groupCollection = (new GroupCollection())->register(new Group('api', prefix: '/api/v1'));
        $actionCollection = new ActionCollection($container, $extractAttribute, $groupCollection);
        $actionCollection->create([ActionWithInvalidMiddleware::class]);

        $handler = new RequestHandler(
            matcher:              new UriMatcher(
                                      new RulesChecker(new RuleExecutorCollection($container, $extractAttribute), new Message($config))
                                  ),
            container:            $container,
            exceptionTransformer: new ExceptionTransformer(),
            responseFactory:      new ResponseFactory(),
            actionCollection:     $actionCollection,
        );

        $request = (new RequestFactory(new UriFactory()))->createServerRequest('POST', '/api/v1/invalid');

        $response = $handler->handle($request);

        $this->assertEquals('"test"', (string)$response->getBody());
        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testMergeQueryParams(): void
    {
        $container = new Container();
        $config = new MemoryConfig();
        $extractAttribute = new ExtractAttribute();
        $groupCollection = (new GroupCollection())->register(new Group('api', prefix: '/api/v1'));
        $actionCollection = new ActionCollection($container, $extractAttribute, $groupCollection);
        $actionCollection->create([TestActionWithQuery::class]);

        $handler = new RequestHandler(
            matcher:              new UriMatcher(
                                      new RulesChecker(
                                          new RuleExecutorCollection($container, $extractAttribute),
                                          new Message($config)
                                      )
                                  ),
            container:            $container,
            exceptionTransformer: new ExceptionTransformer(),
            responseFactory:      new ResponseFactory(),
            actionCollection:     $actionCollection,
        );

        $request = (new RequestFactory(new UriFactory()))->createServerRequest('POST', '/api/v1/user/4?queryParam=1');

        $response = $handler->handle($request);

        $this->assertEquals('{"queryParam":1,"id":4}', (string)$response->getBody());
        $this->assertEquals(201, $response->getStatusCode());
    }
}

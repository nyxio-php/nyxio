<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Provider;

use Nyxio\Config\MemoryConfig;
use Nyxio\Container\Container;
use Nyxio\Contract\Event\EventDispatcherInterface;
use Nyxio\Contract\Kernel\Exception\Transformer\ExceptionTransformerInterface;
use Nyxio\Contract\Kernel\Request\ActionCollectionInterface;
use Nyxio\Contract\Kernel\Request\RequestHandlerInterface;
use Nyxio\Contract\Provider\ProviderDispatcherInterface;
use Nyxio\Contract\Routing\GroupCollectionInterface;
use Nyxio\Contract\Routing\UriMatcherInterface;
use Nyxio\Contract\Server\HandlerInterface;
use Nyxio\Contract\Validation\Handler\RulesCheckerInterface;
use Nyxio\Contract\Validation\Handler\ValidatorCollectionInterface;
use Nyxio\Contract\Validation\RuleExecutorCollectionInterface;
use Nyxio\Kernel\Application;
use Nyxio\Kernel\Provider\KernelProvider;
use Nyxio\Kernel\Provider\ValidationProvider;
use Nyxio\Kernel\Provider\WorkermanProvider;
use Nyxio\Routing\Group;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class ProvidersTest extends TestCase
{
    /**
     * @param string $alias
     * @param bool $singleton
     * @return void
     *
     * @dataProvider getDataProviderForKernel
     */
    public function testKernelProvider(string $alias, bool $singleton): void
    {
        $container = new Container();
        $provider = new KernelProvider(
            $container, (new MemoryConfig())->addConfig('http', ['groups' => [new Group('test')]])
        );

        $provider->process();

        $this->assertTrue($singleton ? $container->hasSingleton($alias) : $container->hasBind($alias));
    }

    public function testWorkermanProvider(): void
    {
        $container = new Container();
        $provider = new WorkermanProvider($container);
        $provider->process();
        $this->assertTrue($container->hasSingleton(HandlerInterface::class));
    }

    private function getDataProviderForKernel(): array
    {
        return [
            [Application::class, true],
            [ActionCollectionInterface::class, true],
            [ExceptionTransformerInterface::class, true],
            [RequestHandlerInterface::class, true],
            [ProviderDispatcherInterface::class, true],
            [EventDispatcherInterface::class, true],

            [UriFactoryInterface::class, true],
            [StreamFactoryInterface::class, true],
            [UploadedFileFactoryInterface::class, true],
            [ServerRequestFactoryInterface::class, true],
            [ResponseFactoryInterface::class, true],

            [GroupCollectionInterface::class, true],
            [UriMatcherInterface::class, false],

            [RuleExecutorCollectionInterface::class, true],
            [RulesCheckerInterface::class, true],
            [ValidatorCollectionInterface::class, false],
        ];
    }
}

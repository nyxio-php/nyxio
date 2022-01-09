<?php

declare(strict_types=1);

namespace Nyxio\Tests\Config;

use Nyxio\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testPreload(): void
    {
        $config = $this->getFilesConfig();
        $config->preloadConfigs(
            [
                'app',
                'checkout/amazon',
            ]
        );

        $this->assertEquals(true, $config->get('app.debug'));
        $this->assertEquals('root', $config->get('app.database.user'));
        $this->assertEquals('pass', $config->get('app.database.pass'));
        $this->assertEquals(false, $config->get('app.database.randomField', false));
    }

    public function testDefaultValue(): void
    {
        $this->assertEquals(1337, $this->getFilesConfig()->get('app.ttt', 1337));
    }

    public function testNotExistsConfig(): void
    {
        $this->assertEquals(
            'random-default-message',
            $this->getFilesConfig()->get('random.config', 'random-default-message')
        );
    }

    public function testAppendConfig(): void
    {
        $config = $this->getFilesConfig();
        $config->addConfig('super-secret', ['name' => 'secret']);
        $this->assertEquals('secret', $config->get('super-secret.name'));
    }

    public function testInvalidName(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->getFilesConfig()->get('app');
    }

    public function testInvalidName2(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->getFilesConfig()->get('');
    }

    public function testInvalidName3(): void
    {
        $this->assertEquals('default', $this->getFilesConfig()->get('app.', 'default'));
    }

    private function getFilesConfig(): Config
    {
        return (new Config())->addConfig('dir', [
            'root' => __DIR__,
            'config' => 'Fixture',
        ]);
    }
}

<?php

declare(strict_types=1);

namespace Nyxio\Tests\Kernel\Text;

use Nyxio\Config\MemoryConfig;
use Nyxio\Kernel\Text\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testBasic(): void
    {
        $config = (new MemoryConfig())->addConfig('lang/en', [

            'secret_answer' => 'I\'m :type message',
            'secret_question' => 'Who are you?',
        ]);

        $message = new Message($config);

        $this->assertEquals(
            'I\'m secret message',
            $message->text('secret_answer', ['type' => 'secret']),
        );

        $this->assertEquals(
            'Who are you?',
            $message->text('secret_question'),
        );
    }

    public function testLanguages(): void
    {
        $config = (new MemoryConfig())
            ->addConfig('lang/en', [

                'secret_answer' => 'I\'m :type message',
                'secret_question' => 'Who are you?',
            ])
            ->addConfig('lang/digital', [
                'secret_answer' => 'AMA MSG',
                'secret_question' => 'WAY??????',
            ])
            ->addConfig('app', [
                'lang' => 'digital',
            ]);

        $message = new Message($config);

        $this->assertEquals(
            'AMA MSG',
            $message->text('secret_answer'),
        );

        $this->assertEquals(
            'WAY??????',
            $message->text('secret_question'),
        );
    }

    public function testUnspecifiedMessage(): void
    {
        $config = (new MemoryConfig());

        $message = new Message($config);

        $this->assertEquals(
            'secret_answer',
            $message->text(':type_answer', ['type' => 'secret']),
        );

        $this->assertEquals(
            'secret_question',
            $message->text('secret_question'),
        );
    }

    public function testAlternativeLanguageByParam(): void
    {
        $config = (new MemoryConfig())
            ->addConfig('lang/en', [

                'secret_answer' => 'I\'m :type message',
                'secret_question' => 'Who are you?',
            ])
            ->addConfig('lang/digital', [
                'secret_answer' => 'AMA MSG',
                'secret_question' => 'WAY??????',
            ])
            ->addConfig('app', [
                'lang' => 'en',
            ]);

        $message = new Message($config);

        $this->assertEquals(
            'AMA MSG',
            $message->text('secret_answer', language: 'digital'),
        );

        $this->assertEquals(
            'WAY??????',
            $message->text('secret_question', language: 'digital'),
        );
    }

    public function testEmptyMessage(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $message = new Message(new MemoryConfig());
        $message->text('');
    }

    public function testDepth(): void
    {
        $config = (new MemoryConfig())->addConfig('lang/en', [
            'super' => [
                'mega' => [
                    'hyper' => [
                        'amazing' => [
                            'my' => '"hello :param" message!',
                        ],
                    ],
                ],
            ],
        ]);

        $message = new Message($config);

        $this->assertEquals(
            '"hello world" message!',
            $message->text('super.mega.hyper.amazing.my', ['param' => 'world']),
        );
    }
}

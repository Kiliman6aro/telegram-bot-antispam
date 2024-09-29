<?php

namespace Message;

use HopHey\TelegramBot\Contract\Repository\KeywordRepositoryContract;
use HopHey\TelegramBot\Contract\Message\TextNormalizerContract;
use HopHey\TelegramBot\Message\MessageHandler;
use PHPUnit\Framework\TestCase;

class MessageHandlerTest extends TestCase
{
    private KeywordRepositoryContract $keywordRepositoryMock;
    private TextNormalizerContract $textNormalizerMock;
    private MessageHandler $messageHandler;


    private array $keywords = [];

    protected function setUp(): void
    {
        $this->keywordRepositoryMock = $this->createMock(KeywordRepositoryContract::class);
        $this->textNormalizerMock = $this->createMock(TextNormalizerContract::class);
        $this->messageHandler = new MessageHandler($this->keywordRepositoryMock, $this->textNormalizerMock);
        $this->keywords = [
            'работа',
            'работу',
            'подработок',
            'сотрудничество',
            'співпраця',
            'робота',
            'роботу',
            'підробіток'
        ];
    }

    public function testProcessMessagesContainsKeyword()
    {
        $message = "Предлагаю выгодную работу!";
        $normalizedMessage = "Предлагаю выгодную работу!";

        $this->textNormalizerMock->method('normalize')->willReturn($normalizedMessage);

        $this->keywordRepositoryMock->method('all')->willReturn($this->keywords);

        $result = $this->messageHandler->processMessages($message);
        $this->assertTrue($result);
    }

    public function testProcessMessagesDoesNotContainKeyword()
    {
        $message = "Goodbye world!";
        $normalizedMessage = "goodbye world!";

        $this->textNormalizerMock->method('normalize')->willReturn($normalizedMessage);

        $this->keywordRepositoryMock->method('all')->willReturn(['hello', 'test']);

        $result = $this->messageHandler->processMessages($message);
        $this->assertFalse($result);
    }
}
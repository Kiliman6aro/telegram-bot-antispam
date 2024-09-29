<?php

namespace HopHey\TelegramBot\Message;

use HopHey\TelegramBot\Contract\Repository\KeywordRepositoryContract;
use HopHey\TelegramBot\Contract\Message\MessageHandlerContract;
use HopHey\TelegramBot\Contract\Message\TextNormalizerContract;

class MessageHandler implements MessageHandlerContract
{

    private KeywordRepositoryContract $repositoryContract;

    private TextNormalizerContract $textNormalizer;

    /**
     * @param KeywordRepositoryContract $repositoryContract
     * @param TextNormalizerContract $normalizerContract
     */
    public function __construct(KeywordRepositoryContract $repositoryContract, TextNormalizerContract $normalizerContract)
    {
        $this->repositoryContract = $repositoryContract;
        $this->textNormalizer = $normalizerContract;
    }


    public function processMessages(string $message): bool
    {
        $message = $this->textNormalizer->normalize($message);
        return $this->containsKeywords($message);
    }

    private function containsKeywords($message): bool
    {
        foreach ($this->repositoryContract->all() as $keyword) {
            if (mb_stripos($message, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

}
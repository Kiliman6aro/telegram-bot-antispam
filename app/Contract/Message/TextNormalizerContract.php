<?php

namespace HopHey\TelegramBot\Contract\Message;

interface TextNormalizerContract
{
    public function normalize(string $text): string;
}
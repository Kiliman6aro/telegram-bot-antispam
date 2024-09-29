<?php

namespace HopHey\TelegramBot\Contract\Message;

interface MessageHandlerContract
{
    public function processMessages(string $message): bool;
}
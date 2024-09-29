<?php

namespace HopHey\TelegramBot\Contract\Http;

interface ClientContract
{
    public function request(string $method, array $params = []): array;
}
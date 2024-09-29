<?php

namespace HopHey\TelegramBot\Message;

use HopHey\TelegramBot\Contract\Message\TextNormalizerContract;

class TextNormalizer implements TextNormalizerContract
{
    private array $replacementMap;

    public function __construct()
    {
        $this->replacementMap = [
            'a' => 'а',
            'e' => 'е',
            'o' => 'о',
            'p' => 'р',
            'c' => 'с',
            'x' => 'х',
            'y' => 'у',
            'i' => 'і',
            'B' => 'В',
            'H' => 'Н',
            'K' => 'К',
            'M' => 'М',
            'T' => 'Т',
            '0' => 'о',
            '3' => 'з',
            '6' => 'б',
        ];
    }
    public function normalize(string $text): string
    {
        return strtr($text, $this->replacementMap);
    }

}
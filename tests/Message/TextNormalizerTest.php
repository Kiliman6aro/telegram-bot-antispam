<?php

namespace Message;

use HopHey\TelegramBot\Message\TextNormalizer;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

class TextNormalizerTest extends TestCase
{
    private TextNormalizer $textNormalizer;

    protected function setUp(): void
    {
        $this->textNormalizer = new TextNormalizer();
    }

    #[TestDox("Проверка замены 6 на б")]
    public function testNormalizeWithSixDigits()
    {
        $input = "Предлагаю ра6666оту!";
        $expected = "Предлагаю рабббботу!";

        $result = $this->textNormalizer->normalize($input);
        $this->assertSame($expected, $result);
    }

    #[TestDox("Проверка замены 0 на о")]
    public function testNormalizeWithZeroDigits()
    {
        $input = "Предлагаю раб00ту!";
        $expected = "Предлагаю рабооту!";

        $result = $this->textNormalizer->normalize($input);
        $this->assertSame($expected, $result);
    }

    #[TestDox("Проверка замены 3 на з")]
    public function testNormalizeWithThreeDigits()
    {
        $input = "Предлагаю 3ароб3іток!";
        $expected = "Предлагаю заробзіток!";

        $result = $this->textNormalizer->normalize($input);
        $this->assertSame($expected, $result);
    }

    #[TestDox("Проверка замены латинских символов на кириллицу на русском")]
    public function testNormalizeWithLatinCharactersRussianLangBased()
    {
        $input = "Пpeдлaгaю paбoтy!";
        $expected = "Предлагаю работу!";

        $result = $this->textNormalizer->normalize($input);
        $this->assertSame($expected, $result);
    }

    #[TestDox("Проверка замены латинских символов на кириллицу на украинском")]
    public function testNormalizeWithLatinCharactersUkraineLangBased()
    {
        $input = "Зaпpoшyю до cпiвпpaцi!";
        $expected = "Запрошую до співпраці!";

        $result = $this->textNormalizer->normalize($input);
        $this->assertSame($expected, $result);
    }

}
<?php

namespace HopHey\TelegramBot\Repository;

use HopHey\TelegramBot\Contract\Repository\KeywordRepositoryContract;

class FileKeywordRepository implements KeywordRepositoryContract
{
    private array $list = [];

    /**
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        if (file_exists($filePath)) {
            $keywords = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $this->list = array_map('trim', $keywords);
        }
        return [];
    }

    public function all(): array
    {
        return $this->list;
    }
}
<?php

namespace HopHey\TelegramBot;
class TelegramBot {
    private $token;
    private $apiUrl;
    private $offset;
    private $keywords;
    /**
     * @var array|string[]
     */
    private $replacementMap;

    public function __construct($token) {
        $this->token = $token;
        $this->apiUrl = "https://api.telegram.org/bot" . $this->token;
        $this->offset = 0;

        $this->replacementMap = [
            'a' => 'а', // Латинская 'a' -> Кириллическая 'а'
            'e' => 'е', // Латинская 'e' -> Кириллическая 'е'
            'o' => 'о', // Латинская 'o' -> Кириллическая 'о'
            'p' => 'р', // Латинская 'p' -> Кириллическая 'р'
            'c' => 'с', // Латинская 'c' -> Кириллическая 'с'
            'x' => 'х', // Латинская 'x' -> Кириллическая 'х'
            'y' => 'у', // Латинская 'y' -> Кириллическая 'у'
            'B' => 'В', // Латинская 'B' -> Кириллическая 'В'
            'H' => 'Н', // Латинская 'H' -> Кириллическая 'Н'
            'K' => 'К', // Латинская 'K' -> Кириллическая 'К'
            'M' => 'М', // Латинская 'M' -> Кириллическая 'М'
            'T' => 'Т', // Латинская 'T' -> Кириллическая 'Т'
            '0' => 'о', // Число '0' -> Кириллическая 'о'
            '3' => 'з', // Число '3' -> Кириллическая 'з'
            '6' => 'б', // Число '6' -> Кириллическая 'б'
        ];

        // Ключевые слова для поиска
        $this->keywords = $this->loadKeywordsFromFile('keywords.txt');
        echo "Keywords load: ".implode(',', $this->keywords).PHP_EOL;

    }

    private function apiRequest($method, $params = []) {
        $url = $this->apiUrl . '/' . $method;
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function getUpdates() {
        return $this->apiRequest('getUpdates', ['offset' => $this->offset, 'timeout' => 30]);
    }

    private function highlightKeywords($message) {
        $normalizedMessage = $this->normalizeText($message);

        $this->keywords = $this->loadKeywordsFromFile('keywords.txt');
        // Проходим по всем ключевым словам
        foreach ($this->keywords as $keyword) {
            $keyword = preg_quote($keyword, '/');
            $pattern = "/($keyword)/iu";

            // Заменяем найденные ключевые слова на их версию с красным выделением
            $normalizedMessage = preg_replace($pattern, "\033[31m$1\033[0m", $normalizedMessage);
        }

        return $normalizedMessage;
    }

    private function loadKeywordsFromFile($filePath) {
        if (file_exists($filePath)) {
            $keywords = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            return array_map('trim', $keywords); // Убираем лишние пробелы
        }else{
            echo 'Не найден словарь'.PHP_EOL;
        }
        return [];
    }

    private function sanitizeMessage($message) {
        // Удаляем переносы строк и другие управляющие символы
        return str_replace(["\r", "\n"], ' ', $message);
    }

    private function normalizeText($text) {
        // Замена латинских символов на соответствующие кириллические
        return strtr($text, $this->replacementMap);
    }

    public function processUpdates() {
        $updates = $this->getUpdates();

        if (isset($updates['result'])) {
            foreach ($updates['result'] as $update) {
                $this->offset = $update['update_id'] + 1;

                if (isset($update['message'])) {
                    $chatId = $update['message']['chat']['id'];
                    $messageId = $update['message']['message_id']; // Получаем message_id
                    $username = $update['message']['from']['username'] ?? 'Unknown';
                    $messageText = '';

                    // Определяем текст сообщения
                    if (isset($update['message']['text'])) {
                        $messageText = $update['message']['text'];
                        $messageType = 'Text';
                    } elseif (isset($update['message']['caption'])) {
                        $messageText = $update['message']['caption'];
                        $messageType = 'Caption';
                    } else {
                        // Пропускаем сообщения без текста или подписи
                        continue;
                    }

                    // Проверяем наличие ключевых слов
                    if ($this->containsKeywords($this->sanitizeMessage($messageText))) {
                        // Подсвечиваем только ключевые слова красным цветом в консоли PowerShell
                        $highlightedMessage = $this->highlightKeywords($messageText);

                        // Выводим информацию о сообщении, включая message_id
                        echo "Username: $username, sent: $highlightedMessage, type: $messageType, message_id: $messageId\n";
                    }
                }
            }
        }
    }


    private function containsKeywords($message) {
        $normalizedMessage = $this->normalizeText($message);

        $this->keywords = $this->loadKeywordsFromFile('keywords.txt');
        foreach ($this->keywords as $keyword) {
            if (mb_stripos($normalizedMessage, $keyword) !== false) {
                return true;
            }
        }
        return false;
    }

    public function run() {
        while (true) {
            $this->processUpdates();
            sleep(1); // Пауза между запросами, чтобы не перегружать сервер
        }
    }
}


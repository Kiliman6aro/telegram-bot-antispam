<?php

namespace HopHey\TelegramBot\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use HopHey\TelegramBot\Contract\Http\ClientContract;

class TelegramClient implements ClientContract
{
    private Client $client;
    private string $apiUrl;


    public function __construct(string $token)
    {
        $this->apiUrl = "https://api.telegram.org/bot" . $token;
    }

    /**
     * @throws GuzzleException
     */
    public function request(string $method, array $params = []): array
    {
        $response = $this->client->request('GET', $this->apiUrl . $method, [
            'query' => $params,
            'timeout' => 10,
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
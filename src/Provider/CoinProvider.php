<?php


namespace App\Provider;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoinProvider{
    public function __construct(private HttpClientInterface $client,
    ) {
        
    }

    public function fetchCoinInfo($id): array
    {
        $response = $this->client->request(
            'GET',
            "https://api.coinlore.net/api/ticker/?id=".$id
        );

        $content = $response->getContent();

        $content = $response->toArray();

        return $content;
    }

    public function fetchCoinList(): array
    {
        $response = $this->client->request(
            'GET',
            "https://api.coinlore.net/api/tickers/?limit=5"
        );

        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }
}
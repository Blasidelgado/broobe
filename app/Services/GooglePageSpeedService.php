<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GooglePageSpeedService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('GOOGLE_API_KEY');
    }

    public function fetchMetrics($url, $categories, $strategy)
    {
        try {
            $response = $this->client->request('GET', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'query' => [
                    'url' => $url,
                    'key' => $this->apiKey,
                    'category' => $categories,
                    'strategy' => $strategy,
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw new UnexpectedValueException('Error fetching metrics: ' . $e->getMessage());
        }
    }
}

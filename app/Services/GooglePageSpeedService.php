<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Query;

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
        $query_params = [
            'url' => $url,
            'key' => $this->apiKey,
            'category' => $categories,
            'strategy' => $strategy
    ];
        try {
            $response = $this->client->request('GET', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'query' => Query::build($query_params)
            ]);

            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            throw new UnexpectedValueException('Error fetching metrics: ' . $e->getMessage());
        }
    }
}

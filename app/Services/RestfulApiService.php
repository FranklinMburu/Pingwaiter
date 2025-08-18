<?php

namespace App\Services;

use GuzzleHttp\Client;

class RestfulApiService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.restful-api.dev/']);
    }

    public function getAllObjects()
    {
        $response = $this->client->request('GET', 'objects');
        return json_decode($response->getBody()->getContents());
    }

    public function createObject($data)
    {
        $response = $this->client->request('POST', 'objects', [
            'json' => $data,
        ]);
        return json_decode($response->getBody()->getContents());
    }

    // Add other methods as needed for PUT, DELETE, etc.
}

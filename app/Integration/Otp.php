<?php

namespace App\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Otp
{
    protected ?Client $client = null;
    protected string $key;
    protected string $url;

    public function __construct(string $url, string $key)
    {
        $this->url = $url;
        $this->key = $key;
    }

    public function client()
    {
        if ($this->client === null) {
            $this->client = new Client([
                'base_uri' => $this->url,
                'headers' => [
                    'X-Organik-Auth' => $this->key,
                ],
            ]);
            return $this->client;
        }
    }

    public function smsTitle()
    {
        $response = $this->client()->get('/sms/headers/get');

        return json_decode($response->getBody()->getContents(), true);
    }

    public function send($to)
    {
        try {
            $response = $this->client()->post('/sms/otp/send', [
                'json' => [
                    'message' => 'Doğrulama kodunuz: ${code}',
                    'recipients' => $to,
                    'header' => 100077,
                    'tpe' => 'sms',
                    "encode" => "numeric",
                    "timeout" => 60,
                    "length" => 6
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }

    public function verify($id, $code)
    {
        try {
            $response = $this->client()->post('/sms/otp/verify', [
                'json' => [
                    'id' => $id,
                    'code' => $code,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            return json_decode($e->getResponse()->getBody()->getContents());
        }
    }
}

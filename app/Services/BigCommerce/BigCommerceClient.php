<?php

namespace App\Services\BigCommerce;

use App\Exceptions\BigCommerceException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class BigCommerceClient
{
    /**
     * HTTP Client
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = $this->initialiseClient();
    }

    /**
     * Get API host.
     * @return string
     */
    protected function getHost()
    {
        return (string) Str::of(config('services.big_commerce.api'))->finish('/');
    }

    /**
     * Create Guzzle Http Client.
     * @return Client
     */
    protected function initialiseClient()
    {
        return new Client([
            'base_uri' => $this->getHost(),
            'handler' => $this->createHandler(),
            'debug' => true,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Auth-Token' => config('services.big_commerce.auth_token'),
                'X-Auth-Client' => config('services.big_commerce.auth_client'),
            ],
        ]);
    }

    /**
     * A Proxy method to pass api requests directly to BigCommerce.
     * @param string $method | GET POST PUT PATCH DELETE
     * @param  string  $uri
     * @param  array  $payload
     * @param  string  $version
     * @return mixed
     */
    public function jsonRequest(string $method, string $uri, array $payload, $version = 'v3')
    {
        try {
            $response = $this->client->request($method, sprintf('%s/%s', $version, $uri), [
                'auth' => 'oauth',
                'json' => $payload,
            ]);

            return json_decode($response->getBody(), true);
        } catch (ClientException $exception) {
            $payloadError = $exception->getResponse()->getBody();

            throw new BigCommerceException($payloadError, [
                'big_commerce_uri' => $uri,
                'big_commerce_method' => $method,
                'big_commerce_payload' => $payload,
                'big_commerce_error' => json_decode($payloadError,true),
            ]);
        }
    }

    /**
     * This is used to log all the requests & responses
     * to the log provider.
     * @return HandlerStack
     */
    private function createHandler()
    {
        $handlerStack = HandlerStack::create();

        $messageFormats = [
            'REQUEST: {method} - {uri} - HTTP/{version} - {req_headers} - {req_body}',
            'RESPONSE: {code} - {res_body}',
        ];

        collect($messageFormats)->each(function ($messageFormat) use ($handlerStack) {
            $handlerStack->unshift(
                Middleware::log(
                    App::get('log')->channel('guzzle'),
                    new MessageFormatter($messageFormat)
                )
            );
        });

        return $handlerStack;
    }

    /**
     * Perform client Request.
     * @param string $method | GET POST PUT PATCH DELETE
     * @param  string  $uri
     * @param  array  $query
     * @param  string  $version
     * @return void
     */
    public function request(string $method, string $uri, array $query = [], $version = 'v3')
    {
        try {
            $response = $this->client->request($method, sprintf("%s/$uri", $version), [
                'auth' => 'oauth',
                'query' => $query,
            ]);

            return json_decode($response->getBody(), true);
        } catch (ClientException $exception) {
            $payloadError = $exception->getResponse()->getBody()->getContents();

            throw new BigCommerceException($payloadError, [
                'big_commerce_uri' => $uri,
                'big_commerce_method' => $method,
                'big_commerce_payload' => $query,
                'big_commerce_error' => json_decode($payloadError,true),
            ]);
        }
    }
}

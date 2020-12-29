<?php

namespace App\Services\BigCommerce\Resources;

use App\Services\BigCommerce\BigCommerceClient;
use App\Services\BigCommerce\Transformers\BigCommerceTransformer;

abstract class BigCommerceResource
{
    /**
     * HTTP Client.
     * @var BigCommerceClient
     */
    protected BigCommerceClient $client;

    /**
     * The URL Endpoint.
     * @var string
     */
    protected static string $endpoint;

    /**
     * BigCommerce API Version.
     * @var string
     */
    protected string $version;

    /**
     * BigCommerceResource constructor.
     * @param  BigCommerceClient  $client
     * @param  string  $version
     */
    public function __construct(BigCommerceClient $client, $version = 'v3')
    {
        $this->client = $client;
        $this->version = $version;
    }

    /**
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     * @throws \App\Exceptions\BigCommerceException
     */
    public function index(array $parameters = [])
    {
        $resources = $this->client->request('GET', static::$endpoint, [
            $parameters,
        ], $this->version);

        return (new BigCommerceTransformer())->collection($resources['data']);
    }

    /**
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     * @throws \App\Exceptions\BigCommerceException
     */
    public function store(array $parameters = [])
    {
        $resource = $this->client->jsonRequest('POST', static::$endpoint, [
            $parameters,
        ], $this->version);

        if (empty($resource['data'])) {
            return collect();
        }

        return (new BigCommerceTransformer())
            ->set($resource['data'][0])
            ->create();
    }

    /**
     * @param $id
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     * @throws \App\Exceptions\BigCommerceException
     */
    public function show($id, array $parameters = [])
    {
        $resource = $this->client->request('GET', static::$endpoint, [
            'id:in' => $id,
            ...$parameters,
        ], $this->version);

        if (empty($resource['data'])) {
            return collect();
        }

        return (new BigCommerceTransformer())
            ->set($resource['data'][0])
            ->create();
    }

    /**
     * @param $id
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     * @throws \App\Exceptions\BigCommerceException
     */
    public function update($id, array $parameters = [])
    {
        $resource = $this->client->jsonRequest('PUT', static::$endpoint, [
            $parameters,
        ], $this->version);

        if (empty($resource['data'])) {
            return collect();
        }

        return (new BigCommerceTransformer())
            ->set($resource['data'][0])
            ->create();
    }

    /**
     * @param $id
     * @return bool
     * @throws \App\Exceptions\BigCommerceException
     */
    public function delete($id)
    {
        $this->client->request('DELETE', static::$endpoint, [
            'id:in' => $id,
        ], $this->version);

        return true;
    }
}

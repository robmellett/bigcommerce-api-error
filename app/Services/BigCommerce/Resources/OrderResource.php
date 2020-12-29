<?php

namespace App\Services\BigCommerce\Resources;

use App\Services\BigCommerce\Transformers\BigCommerceTransformer;
use App\Services\BigCommerce\Transformers\OrderDetailTransformer;

/**
 * Class OrderResource.
 */
class OrderResource extends BigCommerceResource
{
    /**
     * The URL Endpoint.
     * @var string
     */
    protected static string $endpoint = 'orders';

    /**
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     * @throws \App\Exceptions\BigCommerceException
     */
    public function index(array $parameters = [])
    {
        $orders = $this->client->request('GET', self::$endpoint, $parameters, 'v2');

        return (new BigCommerceTransformer())->collection($orders);
    }

    /**
     * @param $orderId
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     */
    public function show($orderId, array $parameters = [])
    {
        $orders = $this->client->request(
            'GET',
            sprintf('%s/%s', self::$endpoint, $orderId),
            $parameters,
            'v2');

        return (new BigCommerceTransformer())
            ->set($orders)
            ->create();
    }

    /**
     * @param $orderId
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     */
    public function showWithDetail($orderId, array $parameters = [])
    {
        $orders = $this->client->request(
            'GET',
            sprintf('%s/%s', self::$endpoint, $orderId),
            $parameters,
            'v2');

        return (new OrderDetailTransformer())
            ->set($orders)
            ->create();
    }

    /**
     * @param $id
     * @param  array  $parameters
     * @return \Illuminate\Support\Collection
     * @throws \App\Exceptions\BigCommerceException
     */
    public function update($orderId, array $parameters = [])
    {
        $orders = $this->client->jsonRequest(
            'PUT',
            sprintf('%s/%s', self::$endpoint, $orderId),
            $parameters,
            'v2');

        return (new BigCommerceTransformer())
            ->set($orders)
            ->create();
    }
}

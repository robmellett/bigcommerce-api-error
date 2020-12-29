<?php

namespace App\Services\BigCommerce;

use Exception;
use Illuminate\Support\Str;

class BigCommerceGateway
{
    /**
     * HTTP Client.
     * @var BigCommerceClient
     */
    protected BigCommerceClient $client;

    /**
     * BigCommerceGateway constructor.
     * @param BigCommerceClient $client
     */
    public function __construct(BigCommerceClient $client)
    {
        $this->client = $client;
    }

    /**
     * Magic Method for accessing resources.
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        return $this->make($name);
    }

    /**
     * Proxy the HTTP request through to BigCommerce.
     * @param $method
     * @param $uri
     * @param array $parameters
     * @param string $version
     * @return mixed
     */
    public function proxy($method, $uri, array $parameters, $version = 'v3')
    {
        $proxyUri = ProxyHelper::formatUri($uri);

        return $this->client->jsonRequest($method, $proxyUri, $parameters, $version);
    }

    /**
     * Resource Factory.
     * @param $resource
     * @param  string  $version
     * @return mixed
     */
    public function make($resource, $version = 'v3')
    {
        $class = (string) Str::of($resource)
            ->singular()
            ->studly()
            ->prepend('App\\Services\\BigCommerce\\Resources\\')
            ->append('Resource');

        if (! class_exists($class)) {
            throw new Exception('The resource does not exist '. Str::studly($resource));
        }

        return new $class($this->client, $version);
    }
}

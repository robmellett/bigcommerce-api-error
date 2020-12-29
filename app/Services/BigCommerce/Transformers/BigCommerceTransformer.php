<?php

namespace App\Services\BigCommerce\Transformers;

use Illuminate\Support\Collection;

/**
 * Class BigCommerceTransformer.
 */
class BigCommerceTransformer
{
    /**
     * Customer Payload to transform from.
     * @var
     */
    protected $resource;

    /**
     * Set the payload.
     * @param $resource
     */
    public function set($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Create a resource instance of the payload.
     * @return Collection
     */
    public function create()
    {
        return new Collection($this->resource);
    }

    /**
     * Return a collection of the instance.
     * @param $collection
     * @return Collection
     */
    public static function collection($collection)
    {
        return collect($collection)->map(function ($resource) {
            return (new static)->set($resource)->create();
        });
    }
}

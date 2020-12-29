<?php

namespace App\Services\BigCommerce\Transformers;

use App\Facades\BigCommerce;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class OrderDetailTransformer extends BigCommerceTransformer
{
    /**
     * Create a resource instance of the payload.
     * @return Collection
     */
    public function create()
    {
        $products = $this->fetchOrderProducts();

        return new Collection([
            'order' => $this->resource,
            'products' => $products,
            'shipping_address' => $this->fetchShippingAdress(),
            'coupons' => $this->fetchCoupons(),
            'contains_giftcards' => $this->containsGiftcards($products),
            'giftcards' => $this->giftcards($products)
        ]);
    }

    private function fetchOrderProducts()
    {
        return BigCommerce::proxy('GET', "orders/{$this->getOrderId()}/products", [], 'v2');
    }

    protected function getOrderId()
    {
        return Arr::get($this->resource, 'id');
    }

    private function fetchShippingAdress()
    {
        return BigCommerce::proxy('GET', "orders/{$this->getOrderId()}/shipping_addresses", [], 'v2');
    }

    private function fetchCoupons()
    {
        return BigCommerce::proxy('GET', "orders/{$this->getOrderId()}/coupons", [], 'v2');
    }

    private function containsGiftCards($products)
    {
        return collect($products)->contains('type', 'digital');
    }

    private function giftcards($products)
    {
        return collect($products)
            ->where('type', 'digital')
            ->values();
    }
}

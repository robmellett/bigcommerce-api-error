<?php

namespace App\Services\BigCommerce\Queries;

use App\Facades\BigCommerce;

class OrderQuery
{
    /**
     * Return the Order By the Checkout Id
     * @param  string  $checkoutId
     * @return array|mixed
     */
    public static function byCheckoutId(string $checkoutId)
    {
        $orders = BigCommerce::orders()->index([
            'cart_id' => $checkoutId,
            'sort' => 'date_created:desc'
        ]);

        return data_get($orders, '0');
    }

    /**
     * @param  string  $checkoutId
     */
    public static function showWithDetailByCheckoutId(string $checkoutId)
    {
        $orderId = data_get(self::byCheckoutId($checkoutId), 'id');

        return BigCommerce::orders()->showWithDetail($orderId);
    }
}

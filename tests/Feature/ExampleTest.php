<?php

namespace Tests\Feature;

use App\Services\BigCommerce\Queries\OrderQuery;
use App\Services\BigCommerce\BigCommerceGateway;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function can_initalise_big_commerce_service_provider()
    {
        $gateway = \App::make('bigcommerce');

        $this->assertInstanceOf(BigCommerceGateway::class, $gateway);
    }

    /**
     * @test
     * This is the test we ran to generate the logs we sent last week.
     */
    public function can_show_detail_by_checkout_id()
    {
        $actual = OrderQuery::showWithDetailByCheckoutId('dc5bcf42-65a5-470e-a923-304343a69b34');

        $this->assertEquals(1200074366, data_get($actual, 'order.id'));
    }
}

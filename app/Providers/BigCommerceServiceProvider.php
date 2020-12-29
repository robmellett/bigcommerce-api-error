<?php

namespace App\Providers;

use App\Services\BigCommerce\BigCommerceClient;
use App\Services\BigCommerce\BigCommerceGateway;
use Illuminate\Support\ServiceProvider;

class BigCommerceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('bigcommerce', function () {
            return new BigCommerceGateway(new BigCommerceClient());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

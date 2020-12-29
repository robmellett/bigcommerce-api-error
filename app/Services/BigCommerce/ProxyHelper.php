<?php

namespace App\Services\BigCommerce;

use Illuminate\Support\Str;

class ProxyHelper
{
    public static function formatUri($uri)
    {
        if (Str::of($uri)->startsWith('/')) {
            $uri = Str::after($uri, '/');
        }

        if (Str::contains($uri, 'api/ecommerce')) {
            return (string) Str::of($uri)->replace('api/ecommerce/', '');
        }

        return $uri;
    }

    /**
     * Used in the middleware to extract the resource
     * @param  string  $uri
     * @return string
     */
    public static function mapUriToResource(string $uri)
    {
        return (string) Str::of($uri)
            ->replaceFirst('/api/ecommerce/', '')
            ->replaceFirst('api/ecommerce/', '')
            ->before('/');
    }
}

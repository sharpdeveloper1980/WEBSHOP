<?php

namespace App\Providers;

use App\Services\WebshopClient;

class WebshopServiceProvider extends \App\Providers\AppServiceProvider
{
    public function register()
    {
        return $this->app->singleton(WebshopClient::class, function ($app) {
            return new WebshopClient();
        });
    }

    public function provides()
    {
        return [
            WebshopClient::class,
        ];
    }
}
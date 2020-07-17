<?php

namespace App\Providers;

use App\Services\DomainHandler;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $domain = parse_url(\Request::server("HTTP_REFERER"), PHP_URL_HOST);
        $config = app('config');
        $config->set('request_domain', $domain);
    }

    public function boot()
    {
        //
    }
}

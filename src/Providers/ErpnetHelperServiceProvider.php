<?php

namespace ErpNET\Helper\Providers;

use Illuminate\Support\ServiceProvider;

class ErpnetHelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

//        if (!$this->app->routesAreCached()) {
//            require __DIR__ . "/../Http/Routes.php";
//        }
//
//        $this->loadViewsFrom( __DIR__."/../../resources/views", "erpnet");
//
//        $this->publishes([
//            __DIR__."/../../database/migrations" => database_path("migrations")
//        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}

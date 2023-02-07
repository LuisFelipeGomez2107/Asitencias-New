<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Test\TestFacades;

class TestFacadesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('test', TestFacades::class);
        // $this->app->bind('test', function($app){
        //     return new TestFacades();
        // });
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


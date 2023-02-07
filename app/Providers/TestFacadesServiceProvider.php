<?php

namespace App\Providers;

use app\Test\Facades\TestFacades;
use Illuminate\Support\ServiceProvider;

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

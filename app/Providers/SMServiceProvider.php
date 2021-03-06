<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SMServiceProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
       $this->app->singleton('App\Providers\SMServiceProvider', 'app\Services\SMService');
   }

}

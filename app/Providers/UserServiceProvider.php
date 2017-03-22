<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
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
        //TODO check if ::class syntax valid here
        $this->app->singleton('App\Providers\UserServiceProvider', 'app\Services\UserService');
    }
}

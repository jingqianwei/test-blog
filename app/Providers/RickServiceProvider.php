<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Rick\Connection;

class RickServiceProvider extends ServiceProvider
{
    /**
     * 在服务容器里注册
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Connection::class, function ($app) {
            return new Connection(config('rick'));
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

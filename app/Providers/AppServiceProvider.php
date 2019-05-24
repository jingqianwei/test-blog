<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Carbon\Carbon;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //更改日期提示为中文
        Carbon::setLocale('zh');

        //设置数据表字符串字段的默认长度
        Schema::defaultStringLength(255);

        // 队列执行前
        Queue::before(function (JobProcessing $event) {
            // $event->connectionName
            //$event->job;
            //$event->data;
        });

        // 队列执行后
        Queue::after(function (JobProcessed $event) {
            // $event->connectionName
            //$event->job;
            //$event->data;
        });

        // 队列执行失败
        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception
        });

        // 注册用户表得观察者
        User::observe(UserObserver::class); // 或者 User::observe(new UserObserver);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (app()->isLocal()) { //phpstorm 提示插件,本地开发才生效
            //参考网址：https://github.com/barryvdh/laravel-ide-helper
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}

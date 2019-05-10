<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [ // 注册之后发送验证邮箱的邮件
            SendEmailVerificationNotification::class,
        ],
        'Illuminate\Mail\Events\MessageSending' => [ //事件在邮件消息发送前触发
            'App\Listeners\LogSendingMessage',
        ],
        'Illuminate\Mail\Events\MessageSent' => [ //事件在邮件消息发送后触发
            'App\Listeners\LogSentMessage',
        ],
        'App\Events\PostViewEvent' => [ // 帖子浏览量触发
            'App\Listeners\PostEventListener',
        ],
        'App\Events\UserRegistered' => [
            'App\Listeners\SendWelcomeMail', // 发送欢迎邮件
            'App\Listeners\UpdateReferrer', // 推荐注册的逻辑
        ],
        'App\Events\TestRegistered' => [ // 测试注册成功
            'App\Listeners\SendPoint', //送积分
            'App\Listeners\SendVitalityValue', //送活力值
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

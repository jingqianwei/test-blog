<?php

namespace App\Providers;

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
     * 事件订阅
     * Event Subscribers 是一种特殊的 Listener, 前面讲的是一个 listener 里只能放一个 hander（），
     * 事件订阅可以把很多处理器（handler）放到一个类里面，然后用一个 listner 把它们集合起来，
     * 这样不同的事件只要对应一个 listner 就可以了。
     * The subscriber classes to register.
     * @var array
     */
    protected $subscribe = [

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

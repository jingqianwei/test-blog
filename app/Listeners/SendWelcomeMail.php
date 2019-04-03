<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\Welcome;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        //检测发件人是否存在
        if (!$event->user->email) {
            return;
        }

        // 发送欢迎邮件
        \Mail::to($event->user->email)->send(new Welcome());
    }
}

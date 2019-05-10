<?php

namespace App\Listeners;

use App\Events\TestRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPoint
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
     * @param  TestRegistered  $event
     * @return void
     */
    public function handle(TestRegistered $event)
    {
        \Log::info(__METHOD__ . '同步注册送积分');
    }
}

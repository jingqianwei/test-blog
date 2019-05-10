<?php

namespace App\Listeners;

use App\Events\TestRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 想把一个event事件从同步改为异步，则只需要调用ShouldQueue接口，并引用InteractsWithQueue
 * Class SendVitalityValue
 * @package App\Listeners
 */
class SendVitalityValue implements ShouldQueue
{
    use InteractsWithQueue; // 可以进行次数限制判断

    /**
     * 任务应该发送到的队列的连接的名称
     *
     * @var string|null
     */
    public $connection = 'database';

    /**
     * 任务应该发送到的队列的名称
     *
     * @var string|null
     */
    public $queue = 'listeners';

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
        \Log::info(__METHOD__ . '异步注册送积分');
    }
}

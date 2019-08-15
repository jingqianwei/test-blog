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
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * 任务失败后可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

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
        $i = 0;
        // TODO 不加这个条件，失败后就默认执行尝试的最大次数
        if ($this->attempts() > 3) {
            \Log::info(__METHOD__ . '测试队列执行失败！');
            return;
        }

        if ($i == 0) {
            $this->release(2);
            \Log::info(__METHOD__ . '测试队列执行第' . $this->attempts() . '次');
            return; //TODO 不加return还会往后面执行
        }

        \Log::info(__METHOD__ . '异步注册送积分');
    }

    // TODO 这个中没有failed()方法，想输出错误直接用try{}catch(){}捕获
}

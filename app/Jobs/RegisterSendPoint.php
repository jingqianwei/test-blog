<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RegisterSendPoint implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以执行的最大秒数 (超时时间)。
     *
     * @var int
     */
    public $timeout = 1200;

    /**
     * 任务失败后可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * 如果模型缺失即删除任务。
     *
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * 任务应该发送到的队列的连接的名称
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务应该发送到的队列的名称
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $i = 0;
        // TODO 不加这个条件，失败后就默认执行尝试的最大次数
        if ($this->attempts() > 3) {
            \Log::info(__METHOD__ . '测试队列执行失败！');
            return;
        }

        if ($i == 0) {
            $this->release();
            \Log::info(__METHOD__ . '测试队列执行第' . $this->attempts() . '次');
            return; //TODO 不加return还会往后面执行
        }

        \Log::info(__METHOD__ . '注册送积分');
    }

    /**
     * 任务失败后会调用这个处理过程
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // 给用户发送任务失败的通知，等等……
        \Log::error(__FUNCTION__ . '队列执行失败，错误信息为：' . $exception->getMessage());
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

/**
 * Class OrderExpireListen
 * @package App\Console\Commands
 * @link https://learnku.com/articles/21488
 * 注意要修改redis配置，命令：config set notify-keyspace-events Ex 设置，config set notify-keyspace-events 查看
 */
class OrderExpireListen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '监听订单创建，在3分钟后如果没付款取消订单。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cacheDb = config('database.redis.cache.database',0);
        $pattern = '__keyevent@'. $cacheDb . '__:expired';
        Redis::subscribe([$pattern],function ($channel){     // 订阅键过期事件
            \Log::info('监控到的值为', [$channel]);
            list($key_type, $order_id) = explode(':', $channel); // 取出订单 ID和key值
            switch ($key_type) {
                case 'ORDER_CONFIRM': // 订单确认
                    $order = Order::find($order_id);
                    if ($order) {
                        $order->cancel(); // 执行取消操作
                    }
                    break;
                default:
                    break;
            }
        });
    }
}

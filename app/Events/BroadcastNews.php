<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
//use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; sync队列

/**
 * 新建一个广播，增加对 ShouldBroadcast 的实现
 * Class BroadcastNews
 * @package App\Events
 * @link https://blog.csdn.net/nsrainbow/article/details/80428769
 * @link https://www.jianshu.com/p/6e3797ce380b
 */
class BroadcastNews implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * 要放置事件的队列的名称。
     *
     * @var string
     */
    public $broadcastQueue = 'broadcast-news-queue';

    protected $message;
    protected $value;

    /**
     * Create a new event instance.
     *
     * @param string $message 消息内容
     * @param int $value 发送消息条件
     */
    public function __construct($message, $value)
    {
        $this->message = $message;
        $this->value = $value;
    }

    /**
     * Get the channels the event should broadcast on.
     * 修改broadcastOn 方法，使用公共广播通道 news
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('news');
    }

    /**
     * 广播事件名称
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'broadcast.news';
    }

    /**
     * 获取广播数据
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['news' => $this->message];
    }

    /**
     * 确定事件是否要被广播
     *
     * @return bool
     */
    public function broadcastWhen()
    {
        return $this->value > 100;
    }
}

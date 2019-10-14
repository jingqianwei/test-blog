<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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

    public $message;

    /**
     * Create a new event instance.
     *
     * @param string $message 消息内容
     */
    public function __construct($message)
    {
        $this->message = $message;
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
}

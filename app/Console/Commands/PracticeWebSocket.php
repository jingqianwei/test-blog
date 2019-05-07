<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PracticeWebSocket extends Command
{
    private $webSocket;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'practice:web-socket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '练习web-socket';

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
        // 初始化连接
        $this->webSocket = new \swoole_websocket_server("0.0.0.0", 9502);

        // 设置连接配置
        $this->webSocket->set(
            [
                'daemonize'=> 1, // 守护进程
                'log_file' => '/data/log/swoole.log', // 日志地址
            ]
        );

        // 打开连接
        $this->webSocket->on('open',function (\swoole_websocket_server $server, $request){
            Log::info('websocket连接', ['握手成功' . $request->fd]);
        });

        //监听WebSocket消息事件
        $this->webSocket->on('message', [$this, 'onRecordComment']);

        //监听WebSocket关闭事件
        $this->webSocket->on('close', function ($ser, $fd) {
            Log::info("websocket连接 client-{$fd} 断开");
        });

        $this->webSocket->start();
    }

    /**
     * 回复的回调方法
     * @param \swoole_websocket_server $ws
     * @param $frame
     * @return bool
     */
    protected function  onRecordComment(\swoole_websocket_server $ws, $frame)
    {
        $data = json_decode($frame->data,true);
        Log::info('websocket接受到的数据为', $data);
        foreach ($ws->connections as $fd){
            Log::info('连接的fd为' . $fd);
            $ws->push($fd, json_encode(['errcode'=>'0', 'errmsg'=>'成功', 'data'=>$data],JSON_UNESCAPED_UNICODE));
        }

        return true;
    }
}

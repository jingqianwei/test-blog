<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        //创建WebSocket服务器对象，监听0.0.0.0:9502端口
        $this->webSocket = new \swoole_websocket_server("0.0.0.0", 9502);

        //监听WebSocket连接打开事件
        $this->webSocket->on('open', function (\swoole_websocket_server $ws, $request) {
            $this->info("server: handshake success with fd{$request->fd}\n");
            $ws->push($request->fd, "hello, welcome\n");
        });

        //监听WebSocket消息事件
        $this->webSocket->on('message', function (\swoole_websocket_server $ws, $frame) {
            $this->info("Message: {$frame->data}\n");
            $ws->push($frame->fd, "server: {$frame->data}");
        });

        //接收request请求
        $this->webSocket->on('request', function ($request, $response, \swoole_websocket_server $ws) {
            //接收http请求从post获取参数
            // token验证推送来源，避免恶意访问
            // 接收http请求从post获取message参数的值，给用户推送
            // $this->webSocket->connections 遍历所有WebSocket连接用户的fd，给所有用户推送
            var_dump($request->post);
            foreach ($ws->connections as $fd) {
                $this->info("client-{$fd} is pushed\n");
                $ws->push($fd, $request->post['info']);
            }
        });

        //监听WebSocket连接关闭事件
        $this->webSocket->on('close', function ($ser, $fd) {
            $this->info("client-{$fd} is closed\n");
        });

        // 开启
        $this->webSocket->start();
    }
}

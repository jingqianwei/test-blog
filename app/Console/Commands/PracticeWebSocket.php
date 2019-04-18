<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PracticeWebSocket extends Command
{
    private $webSocket;
    public $server;
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
        $this->start();
//        //创建WebSocket服务器对象，监听0.0.0.0:9502端口
//        $this->webSocket = new \swoole_websocket_server("0.0.0.0", 9502);
//
//        //监听WebSocket连接打开事件
//        $this->webSocket->on('open', function (\swoole_websocket_server $ws, $request) {
//            $this->info("server: handshake success with fd{$request->fd}\n");
//            $ws->push($request->fd, "hello, welcome\n");
//        });
//
//        //监听WebSocket消息事件
//        $this->webSocket->on('message', function (\swoole_websocket_server $ws, $frame) {
//            $this->info("Message: {$frame->data}\n");
//            // 给单独这个WebSocket连接推送消息
//            $ws->push($frame->fd, "server: {$frame->data}");
//            // $this->webSocket->connections 遍历所有WebSocket连接用户的fd，给所有用户推送消息
//            foreach ($ws->connections as $fd) {
//                $this->info("client-{$fd} is pushed\n");
//                $ws->push($fd, "server: {$frame->data}");
//            }
//        });
//
//        //接收http中request请求
//        $this->webSocket->on('request', function ($request, $response) {
//            $response->end("<h1>Hello Swoole. #".rand(1000, 9999). $request->post['info'] ."</h1>");
//            //接收http请求从post获取参数
//            // token验证推送来源，避免恶意访问
//            // 接收http请求从post获取message参数的值，给用户推送
//            // $this->webSocket->connections 遍历所有WebSocket连接用户的fd，给所有用户推送
//            var_dump($request->post);
//            foreach ($this->webSocket->connections as $fd) {
//                $this->info("client-{$fd} is pushed\n");
//                $this->webSocket->push($fd, $request->post['info']);
//            }
//        });
//
//        //监听WebSocket连接关闭事件
//        $this->webSocket->on('close', function ($ser, $fd) {
//            $this->info("client-{$fd} is closed\n");
//        });
//
//        // 开启
//        $this->webSocket->start();
    }

    private function start()
    {
        $server = new swoole_websocket_server("127.0.0.1", 9502);

        $server->on('open', function($server, $req) {
            echo "connection open: {$req->fd}\n";
        });

        $server->on('message', function($server, $frame) {
            echo "received message: {$frame->data}\n";
            $server->push($frame->fd, json_encode(["hello", "world"]));
        });

        $server->on('close', function($server, $fd) {
            echo "connection close: {$fd}\n";
        });

        $server->start();
    }
}

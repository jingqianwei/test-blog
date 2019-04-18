<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PracticeWebSocket extends Command
{
    protected $web_socket;
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
        $ws = new \swoole_websocket_server("0.0.0.0", 9502);

        //监听WebSocket连接打开事件
        $ws->on('open', function ($ws, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
            $ws->push($request->fd, "hello, welcome\n");
        });

        //监听WebSocket消息事件
        $ws->on('message', function ($ws, $frame) {
            echo "Message: {$frame->data}\n";
            $ws->push($frame->fd, "server: {$frame->data}");
        });

        //监听WebSocket连接关闭事件
        $ws->on('close', function ($ws, $fd) {
            echo "client-{$fd} is closed\n";
        });

        $ws->start();
    }

    private function start()
    {
        $this->web_socket = new \swoole_websocket_server("0.0.0.0", 9502);

        $this->web_socket->on('open', function (\swoole_websocket_server $server, $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        //监听WebSocket消息事件
        $this->web_socket->on('message', array($this,'onRecordComment'));

        $this->web_socket->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });

        $this->web_socket->start();
    }

    private function onRecordComment()
    {

    }
}

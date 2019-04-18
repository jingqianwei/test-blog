<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PracticeWebSocket extends Command
{
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
        // 创建WebSocket服务器对象。监听0.0.0.0:9520
        $server = new \swoole_websocket_server("0.0.0.0", 9502);

        // 创建WebSocket连接打开事件
        $server->on('open', function ($server, $request) {
            echo $request->fd . "连接成功";
        });

        //监听WebSocket消息事件
        $server->on('message', function ($server, $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
            $server->push($frame->fd, "this is server");
        });

        //监听WebSocket连接关闭事件
//        $server->on('close', function ($server, $fd) {
//            $this->info("client {$fd} closed\n");
//        });

        // 开启服务
        $server->start();
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
}

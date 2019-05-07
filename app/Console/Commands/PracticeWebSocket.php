<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PracticeWebSocket extends Command
{
    private $web_socket;
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
    }

    public function start()
    {
        $this->web_socket = new \swoole_websocket_server("0.0.0.0", 9502);
        $this->web_socket->set(array( // 守护进程
            'daemonize'=> 1
        ));
        $this->web_socket->on('open',function (\swoole_websocket_server $server, $request){
            Log::info('websocket连接', ['握手成功'.$request->fd]);
        });

        //监听WebSocket消息事件
        $this->web_socket->on('message', array($this,'onRecordComment'));

        $this->web_socket->on('close', function ($ser, $fd) {
            $this->info("client-{$fd} is closed\n");
        });

        $this->web_socket->start();
    }

    public function  onRecordComment(\swoole_websocket_server $ws, $frame){
        $data = json_decode($frame->data,true);
        Log::info('连接的fd数为 start：' . count($ws->connections));
        Log::info('websocket接受到的数据为', $data);
        Log::info('连接的fd数为 end ' . count($ws->connections));
        foreach ($ws->connections as $fd){
            Log::info('连接的fd为' . $fd);
            $ws->push($fd, json_encode(array('errcode'=>'0','errmsg'=>'成功','data'=>$data),JSON_UNESCAPED_UNICODE));
        }

        return true;
    }
}

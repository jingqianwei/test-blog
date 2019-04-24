<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PracticeWebSocket extends Command
{
    private $webSocket;
    public $server;
    private $serv;
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

//    private function start()
//    {
//        $server = new Swoole\WebSocket\Server("0.0.0.0", 9501);
//        $server->on('open', function (Swoole\WebSocket\Server $server, $request) {
//            echo "server: handshake success with fd{$request->fd}\n";
//            $from = $request->get['from'];
//
//        });
//        $server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
//            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
//            $server->push($frame->fd, "this is server");
//        });
//        $server->on('close', function ($ser, $fd) {
//            echo "client {$fd} closed\n";
//        });
//        $server->start();
//    }

     protected function init()
     {
         $this->serv = new \swoole_websocket_server("0.0.0.0", 9501);

         $this->serv->set([

             'worker_num'      => 2, //开启2个worker进程

             'max_request'     => 4, //每个worker进程 max_request设置为4次

             'task_worker_num' => 4, //开启4个task进程

             'dispatch_mode'   => 4, //数据包分发策略 - IP分配

             'daemonize'       => false, //守护进程(true/false)

         ]);

         $this->serv->on('Start', [$this, 'onStart']);

         $this->serv->on('Open', [$this, 'onOpen']);

         $this->serv->on("Message", [$this, 'onMessage']);

         $this->serv->on("Close", [$this, 'onClose']);

         $this->serv->on("Task", [$this, 'onTask']);

         $this->serv->on("Finish", [$this, 'onFinish']);

         $this->serv->start();
     }

    public function onStart($serv) {

        echo "#### onStart ####".PHP_EOL;

        echo "SWOOLE ".SWOOLE_VERSION . " 服务已启动".PHP_EOL;

        echo "master_pid: {$serv->master_pid}".PHP_EOL;

        echo "manager_pid: {$serv->manager_pid}".PHP_EOL;

        echo "########".PHP_EOL.PHP_EOL;

    }

    public function onOpen($serv, $request) {

        echo "#### onOpen ####".PHP_EOL;

        echo "server: handshake success with fd{$request->fd}".PHP_EOL;

        $serv->task([

            'type' => 'login'

        ]);

        echo "########".PHP_EOL.PHP_EOL;

    }

    public function onTask($serv, $task_id, $from_id, $data) {

        echo "#### onTask ####".PHP_EOL;

        echo "#{$serv->worker_id} onTask: [PID={$serv->worker_pid}]: task_id={$task_id}".PHP_EOL;

        $msg = '';

        switch ($data['type']) {

            case 'login':

                $msg = '我来了...';

                break;

            case 'speak':

                $msg = $data['msg'];

                break;

        }

        foreach ($serv->connections as $fd) {

            $connectionInfo = $serv->connection_info($fd);

            if ($connectionInfo['websocket_status'] == 3) {

                $serv->push($fd, $msg); //长度最大不得超过2M

            }

        }

        $serv->finish($data);

        echo "########".PHP_EOL.PHP_EOL;
    }

    public function onMessage($serv, $frame) {

        echo "#### onMessage ####".PHP_EOL;

        echo "receive from fd{$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}".PHP_EOL;

        $serv->task(['type' => 'speak', 'msg' => $frame->data]);

        echo "########".PHP_EOL.PHP_EOL;
    }

    public function onFinish($serv, $task_id, $data) {

        echo "#### onFinish ####".PHP_EOL;

        echo "Task {$task_id} 已完成".PHP_EOL;

        echo "########".PHP_EOL.PHP_EOL;
    }

    public function onClose($serv, $fd) {

        echo "#### onClose ####".PHP_EOL;

        echo "client {$fd} closed".PHP_EOL;

        echo "########".PHP_EOL.PHP_EOL;
    }
}

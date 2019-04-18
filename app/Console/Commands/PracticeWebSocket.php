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
            $this->info($request->fd . "连接成功");
        });

        $server->start();
    }
}

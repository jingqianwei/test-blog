<?php

namespace App\Console\Commands;

use App\Utils\FileReader;
use Illuminate\Console\Command;

class Multiprocess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multi:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '多进程测试';

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
        $file = new FileReader(storage_path() . '/logs/test.txt');
        $i = 0; $limit = 200;
        while ($data = $file->get_data($limit, $i * $limit)) {
            \Log::info('进程号为：' . posix_getpid() . ' $i值为' . $i);
            $i++;
        }

        return true;
    }
}

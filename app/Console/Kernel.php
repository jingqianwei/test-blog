<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // 每天凌晨1点清理一下项目所有缓存
        //$schedule->command('clear:project-cache')->dailyAt('01:00');
        $schedule->command('clear:project-cache')->everyMinute();

        // 每周执行一次数据库备份
        //$schedule->command('db:backup')->weekly();
        $schedule->command('db:backup')->everyMinute();

        // 每分钟测试
        $schedule->command('test:command')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

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
        $schedule->command('clear:project-cache')->dailyAt('01:00');

        // 每月清理一次备份数据，禁止发邮件
        $schedule->command('backup:clean --disable-notifications --only-db')->monthly();

        // 每周备份一次数据库，禁止发邮件
        $schedule->command('backup:run --disable-notifications --only-db')->weekly();
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

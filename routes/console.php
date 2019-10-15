<?php

use App\Events\BroadcastNews;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

// 自定义发送广播消息
Artisan::command('big-news', function () {
    broadcast(new BroadcastNews(date('Y-m-d h:i:s A').": BIG NEWS!", 10));
    $this->comment("news sent");
})->describe('Send news');

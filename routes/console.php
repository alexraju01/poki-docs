<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule::command('app:cache-all-pokemon')->cron('0 0 */90 * *');
// schedule::command('app:cache-all-pokemon')->everyMinute();


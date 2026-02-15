<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the backup command to run every minute
Schedule::command('backup:run-scheduled')->everyMinute();

// Schedule cleanup to run daily at 3 AM
Schedule::command('backup:cleanup')->dailyAt('03:00');

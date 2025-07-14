<?php

use App\Jobs\SendDailyProductReminder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SendDailyProductReminder)
    ->dailyAt('07:00')
    ->timezone('Asia/Jakarta');

Schedule::call(function () {
    foreach (File::glob(storage_path('logs/*.log')) as $file) {
        File::delete($file);
    }
})->dailyAt('07:00')->timezone('Asia/Jakarta');

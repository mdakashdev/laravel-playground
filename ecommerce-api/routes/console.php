<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


//Schedule::command('report:daily')->daily();
Schedule::command('report:daily')
    ->everyTenSeconds()
    ->sendOutputTo(storage_path('logs/report.log'));;

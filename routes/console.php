<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Disabled by default until verified-email flow and SMTP are configured.
if (env('JOB_ALERTS_SCHEDULE_ENABLED',false)) {
    \Illuminate\Support\Facades\Schedule::command('jobs:send-alerts')
        ->hourly()->withoutOverlapping()->onOneServer();
}

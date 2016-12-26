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
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call('App\Http\Controllers\CronController@currency')->daily();
        $schedule->call('App\Http\Controllers\CronController@ical_sync')->hourly();
        $schedule->call('App\Http\Controllers\CronController@expire')->hourly();
        $schedule->call('App\Http\Controllers\CronController@travel_credit')->daily();
        $schedule->exec('php artisan backup:run')->monthly();
        $schedule->exec('php artisan backup:run --only-db')->daily();
        $schedule->exec('php artisan backup:clean')->monthly();
        $schedule->command('queue:work --tries=3')->cron('* * * * * *');
    }
}

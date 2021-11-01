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
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('movie:views-reset dailyAt')->dailyAt('08:00');
        $schedule->command('movie:views-reset weekly')->weekly();
        $schedule->command('backup:clean')->dailyAt('01:30');
        $schedule->command('backup:run --only-db')->everyMinute();
        $schedule->command('notify:user')->everyMinute();
        $schedule->command('subscription:monitor-expiration')->everyMinute();
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

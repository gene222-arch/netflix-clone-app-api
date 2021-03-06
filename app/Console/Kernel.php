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
        $schedule->command('backup:clean')->everyMinute();
        $schedule->command('backup:run --only-db')->everyMinute();
        $schedule->command('subscription:monitor-expiration')->everyMinute();
        $schedule->command('subscription:notify-expiration')->dailyAt('08:00');
        
        collect(['6:00', '12:00', '18:00'])->each(function ($time) use ($schedule) {
            $schedule->command('notify:user-unsubscribed')->dailyAt($time);
        });
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

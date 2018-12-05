<?php

namespace App\Console;

use App\Console\Commands\ClearExpiredData;
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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule) : void
    {
        $schedule->command(ClearExpiredData::class)->daily();
    }

    /**
     * Register the commands for the application.
     *
     */
    protected function commands() : void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

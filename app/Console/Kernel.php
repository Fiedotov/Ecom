<?php

namespace App\Console;

use App\Console\Commands\PullPropertiesFromSalesforce;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $hour = Carbon::now()->timezone('America/Los_Angeles')->hour;
        $minute = Carbon::now()->timezone('America/Los_Angeles')->minute;

        match([$hour, $minute]) {
            [3, 0] => $schedule->command(PullPropertiesFromSalesforce::class, ['--images'])->everyThirtyMinutes(),
            default => $schedule->command(PullPropertiesFromSalesforce::class)->everyThirtyMinutes()
        };
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

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('fct:log-file-generator')->monthly();
        $schedule->command('reformes:send-notifications')
            ->dailyAt('09:00')
            ->timezone('Africa/Porto-Novo') // Timezone du Bénin
            ->withoutOverlapping()
            ->runInBackground();

         // Nouvelles notifications de publication
        $schedule->command('reformes:send-publication-notifications')
        ->dailyAt('09:00')
        ->timezone('Africa/Porto-Novo')
        ->withoutOverlapping()
        ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

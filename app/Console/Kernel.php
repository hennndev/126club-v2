<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Accurate Sync Configuration
        $syncIntervalMinutes = config('accurate.sync_interval_minutes');
        $syncIntervalHours = config('accurate.sync_interval_hours', 2);

        // Sync master data (urutan penting: dependencies dulu)
        $task3 = $schedule->command('accurate:sync-customers --force');
        $task5 = $schedule->command('accurate:sync-items --force');
        $task7 = $schedule->command('accurate:sync-work-orders --force');
        $task8 = $schedule->command('accurate:sync-sales-orders --force');

        // Apply interval to all tasks
        foreach ([$task3, $task5, $task7, $task8] as $task) {
            // Jika ada setting menit (untuk testing), gunakan menit
            if ($syncIntervalMinutes) {
                $task->everyMinute()
                    ->when(function () use ($syncIntervalMinutes) {
                        return now()->minute % $syncIntervalMinutes === 0;
                    });
            } else {
                // Gunakan jam (production)
                $task->cron("0 */{$syncIntervalHours} * * *");
            }

            $task->withoutOverlapping()
                ->runInBackground();
        }

        // Callbacks untuk task terakhir
        $task8->onSuccess(function () {
            Log::info('Accurate sync completed successfully');
        })->onFailure(function () {
            Log::error('Accurate sync failed');
        });
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

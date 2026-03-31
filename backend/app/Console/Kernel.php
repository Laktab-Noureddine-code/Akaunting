<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel
{
    protected $commands = [];
    protected function schedule(Schedule $schedule)
    {
        if (!config('app.installed')) {
            return;
        }
        $schedule_time = config('app.schedule_time');
        $schedule->command('reminder:invoice')->dailyAt($schedule_time);
        $schedule->command('reminder:bill')->dailyAt($schedule_time);
        $schedule->command('recurring:check')->dailyAt($schedule_time)->runInBackground();
        $schedule->command('storage-temp:clear')->dailyAt('17:00');
        $schedule->command('model:prune')->dailyAt('17:00');
    }
    protected function commands()
    {
        require base_path('routes/console.php');
        $this->load(__DIR__ . '/Commands');
    }
    protected function scheduleTimezone()
    {
        return config('app.timezone');
    }
}

<?php
namespace App\Listeners\Common;
use Illuminate\Console\Events\CommandStarting as Event;
use Illuminate\Console\Scheduling\Schedule;
class SkipScheduleInReadOnlyMode
{
    public function handle(Event $event)
    {
        if (! config('read-only.enabled')) {
            return;
        }
        $schedule = app(Schedule::class);
        foreach ($schedule->events() as $task) {
            $task->skip(true);
        }
    }
}

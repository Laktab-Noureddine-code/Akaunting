<?php
namespace App\Listeners\Auth;
use App\Utilities\Date;
use Illuminate\Auth\Events\Login as Event;
class Login
{
    public function handle(Event $event)
    {
        $event->user->last_logged_in_at = Date::now();
        $event->user->save();
    }
}

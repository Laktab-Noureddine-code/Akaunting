<?php
namespace App\Listeners\Auth;
use Illuminate\Auth\Events\Logout as Event;
class Logout
{
    public function handle(Event $event)
    {
        session()->forget('company_id');
    }
}

<?php
namespace App\Events\Menu;
use App\Abstracts\Event;
class NotificationsCreating extends Event
{
    public $notifications;
    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }
}

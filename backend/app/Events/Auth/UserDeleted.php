<?php
namespace App\Events\Auth;
use App\Abstracts\Event;
class UserDeleted extends Event
{
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }
}

<?php
namespace App\Events\Auth;
use App\Abstracts\Event;
class UserDeleting extends Event
{
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }
}

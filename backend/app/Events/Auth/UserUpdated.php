<?php
namespace App\Events\Auth;
use App\Abstracts\Event;
class UserUpdated extends Event
{
    public $user;
    public $request;
    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->request  = $request;
    }
}

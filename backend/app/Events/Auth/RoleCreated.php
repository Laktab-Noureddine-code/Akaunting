<?php
namespace App\Events\Auth;
use App\Abstracts\Event;
class RoleCreated extends Event
{
    public $role;
    public $request;
    public function __construct($role, $request)
    {
        $this->role = $role;
        $this->request  = $request;
    }
}

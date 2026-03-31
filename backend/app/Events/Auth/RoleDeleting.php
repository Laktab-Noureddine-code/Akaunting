<?php
namespace App\Events\Auth;
use App\Abstracts\Event;
class RoleDeleting extends Event
{
    public $role;
    public function __construct($role)
    {
        $this->role = $role;
    }
}

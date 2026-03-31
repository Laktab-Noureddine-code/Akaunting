<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class AccountCreating extends Event
{
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
}

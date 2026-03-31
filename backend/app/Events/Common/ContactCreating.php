<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class ContactCreating extends Event
{
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
}

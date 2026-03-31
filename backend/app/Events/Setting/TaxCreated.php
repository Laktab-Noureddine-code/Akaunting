<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class TaxCreated extends Event
{
    public $tax;
    public $request;
    public function __construct($tax, $request)
    {
        $this->tax = $tax;
        $this->request  = $request;
    }
}

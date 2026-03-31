<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class TaxDeleted extends Event
{
    public $tax;
    public function __construct($tax)
    {
        $this->tax = $tax;
    }
}

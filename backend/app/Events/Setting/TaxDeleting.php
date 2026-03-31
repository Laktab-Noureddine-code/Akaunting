<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class TaxDeleting extends Event
{
    public $tax;
    public function __construct($tax)
    {
        $this->tax = $tax;
    }
}

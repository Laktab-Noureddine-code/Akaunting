<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class CurrencyDeleting extends Event
{
    public $currency;
    public function __construct($currency)
    {
        $this->currency = $currency;
    }
}

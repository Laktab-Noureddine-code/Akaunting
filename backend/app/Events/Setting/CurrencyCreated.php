<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class CurrencyCreated extends Event
{
    public $currency;
    public $request;
    public function __construct($currency, $request)
    {
        $this->currency = $currency;
        $this->request  = $request;
    }
}

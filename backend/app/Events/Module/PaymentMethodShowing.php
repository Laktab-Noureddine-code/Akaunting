<?php
namespace App\Events\Module;
use App\Abstracts\Event;
class PaymentMethodShowing extends Event
{
    public $modules;
    public function __construct($modules)
    {
        $this->modules = $modules;
    }
}

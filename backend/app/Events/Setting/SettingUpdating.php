<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class SettingUpdating extends Event
{
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
}

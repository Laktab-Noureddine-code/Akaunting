<?php
namespace App\Events\Menu;
use App\Abstracts\Event;
class SettingsCreated extends Event
{
    public $menu;
    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}

<?php
namespace App\Events\Menu;
use App\Abstracts\Event;
class SettingsCreating extends Event
{
    public $menu;
    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}

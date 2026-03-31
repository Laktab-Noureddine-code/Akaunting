<?php
namespace App\Events\Menu;
use App\Abstracts\Event;
class ProfileCreating extends Event
{
    public $menu;
    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}

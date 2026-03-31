<?php
namespace App\Events\Menu;
use App\Abstracts\Event;
class NewwCreating extends Event
{
    public $menu;
    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}

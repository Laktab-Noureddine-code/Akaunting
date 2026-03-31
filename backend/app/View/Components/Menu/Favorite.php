<?php
namespace App\View\Components\Menu;
use App\Abstracts\View\Component;
class Favorite extends Component
{
    public $title;
    public $icon;
    public $route;
    public $url;
    public function __construct(string $title, string $icon, $route = null, $url = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->route = $route;
        $this->url = $url;
    }
    public function render()
    {
        return view('components.menu.favorite');
    }
}

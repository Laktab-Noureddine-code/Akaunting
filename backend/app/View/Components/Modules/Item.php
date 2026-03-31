<?php
namespace App\View\Components\Modules;
use App\Abstracts\View\Component;
class Item extends Component
{
    public $module;
    public function __construct(
        $model
    ) {
        $this->module = $model;
    }
    public function render()
    {
        return view('components.modules.item');
    }
}

<?php
namespace App\View\Components\Dropdown;
use App\Abstracts\View\Component;
class Link extends Component
{
    public $href;
    public $target;
    public function __construct(
        string $href = '', string $target = '_self'
    ) {
        $this->href     = $href;
        $this->target   = $target;
    }
    public function render()
    {
        return view('components.dropdown.link');
    }
}

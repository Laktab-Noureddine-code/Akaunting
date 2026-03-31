<?php
namespace App\View\Components\Button;
use App\Abstracts\View\Component;
class Hover extends Component
{
    public $color;
    public $groupHover;
    public function __construct(
        string $color = 'to-black',
        bool $groupHover = false,
    ) {
        $this->color = $color;
        $this->groupHover = $groupHover;
    }
    public function render()
    {
        return view('components.button.hover');
    }
}

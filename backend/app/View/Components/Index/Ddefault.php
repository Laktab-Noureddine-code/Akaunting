<?php
namespace App\View\Components\Index;
use App\Abstracts\View\Component;
class Ddefault extends Component
{
    public $id;
    public $position;
    public $icon;
    public $iconType;
    public $text;
    public function __construct(
        $position = 'right', $icon = 'lock', $iconType = '-round', $text = ''
    ) {
        $this->id = 'tooltip-default-' . mt_rand(1, time());
        $this->position = $position;
        $this->icon = $icon;
        $this->iconType = $iconType;
        $this->text = $text;
    }
    public function render()
    {
        return view('components.index.default');
    }
}

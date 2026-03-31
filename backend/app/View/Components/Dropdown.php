<?php
namespace App\View\Components;
use App\Abstracts\View\Component;
class Dropdown extends Component
{
    public $id;
    public $override;
    public function __construct(
        $id = '', $override = ''
    ) {
        $this->id = $id;
        $this->override = explode(',', $override);
    }
    public function render()
    {
        return view('components.dropdown.index');
    }
}

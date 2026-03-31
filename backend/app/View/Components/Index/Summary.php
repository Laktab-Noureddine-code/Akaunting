<?php
namespace App\View\Components\Index;
use App\Abstracts\View\Component;
class Summary extends Component
{
    public $items;
    public function __construct(
        $items = []
    ) {
        $this->items = $items;
    }
    public function render()
    {
        return view('components.index.summary');
    }
}

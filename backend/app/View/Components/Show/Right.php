<?php
namespace App\View\Components\Show;
use App\Abstracts\View\Component;
class Right extends Component
{
    public $disableLoading = false;
    public function render()
    {
        return view('components.show.content.right');
    }
}

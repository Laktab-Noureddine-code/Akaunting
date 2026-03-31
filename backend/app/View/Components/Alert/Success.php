<?php
namespace App\View\Components\Alert;
use App\View\Components\Alert as Component;
class Success extends Component
{
    public function render()
    {
        return view('components.alert.success');
    }
}

<?php
namespace App\View\Components\Alert;
use App\View\Components\Alert as Component;
class Danger extends Component
{
    public function render()
    {
        return view('components.alert.danger');
    }
}

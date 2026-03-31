<?php
namespace App\View\Components\Layouts;
use App\Traits\Modules as RemoteModules;
use App\Utilities\Date;
use App\Abstracts\View\Component;
use Illuminate\Support\Facades\Cache;
class Modules extends Component
{
    use RemoteModules;
    public function render()
    {
        return view('components.layouts.modules');
    }
}

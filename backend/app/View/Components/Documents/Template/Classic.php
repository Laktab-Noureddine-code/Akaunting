<?php
namespace App\View\Components\Documents\Template;
use App\Abstracts\View\Components\Documents\Template as Component;
class Classic extends Component
{
    public function render()
    {
        return view('components.documents.template.classic');
    }
}

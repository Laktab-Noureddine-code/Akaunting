<?php
namespace App\View\Components\Documents\Index;
use App\Abstracts\View\Components\Documents\Index as Component;
class BulkAction extends Component
{
    public function render()
    {
        return view('components.documents.index.bulk-action');
    }
}

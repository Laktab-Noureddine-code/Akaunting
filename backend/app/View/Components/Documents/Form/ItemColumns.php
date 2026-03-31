<?php
namespace App\View\Components\Documents\Form;
use App\Abstracts\View\Component;
class ItemColumns extends Component
{
    public $type;
    public function __construct(string $type = 'invoice')
    {
        $this->type = $type;
    }
    public function render()
    {
        return view('components.documents.form.item-columns');
    }
}

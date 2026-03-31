<?php
namespace App\View\Components\Documents\Form;
use App\Abstracts\View\Components\Documents\Form as Component;
class Content extends Component
{
    public function render()
    {
        $status = 'draft';
        if (! empty($this->document)) {
            $status = $this->document->status;
        }
        return view('components.documents.form.content', compact('status'));
    }
}

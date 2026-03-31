<?php
namespace App\View\Components\Documents\Form;
use App\Abstracts\View\Components\Documents\Form as Component;
use App\Models\Setting\Tax;
class Items extends Component
{
    public function render()
    {
        $taxes = Tax::enabled()->orderBy('name')->get();
        return view('components.documents.form.items', compact('taxes'));
    }
}

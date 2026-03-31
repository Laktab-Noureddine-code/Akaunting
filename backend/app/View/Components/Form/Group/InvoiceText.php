<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class InvoiceText extends Form
{
    public $type = 'invoice-text';
    public function render()
    {
        return view('components.form.group.invoice_text');
    }
}

<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Hidden extends Form
{
    public $type = 'hidden';
    public function render()
    {
        return view('components.form.input.hidden');
    }
}

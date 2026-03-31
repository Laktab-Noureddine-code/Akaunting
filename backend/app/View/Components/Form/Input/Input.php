<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Input extends Form
{
    public $type = 'input';
    public function render()
    {
        return view('components.form.input.input');
    }
}

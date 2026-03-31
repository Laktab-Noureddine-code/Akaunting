<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Number extends Form
{
    public $type = 'number';
    public function render()
    {
        return view('components.form.input.number');
    }
}

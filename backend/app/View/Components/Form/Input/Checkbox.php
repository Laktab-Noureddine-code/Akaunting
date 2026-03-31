<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Checkbox extends Form
{
    public $type = 'checkbox';
    public function render()
    {
        return view('components.form.input.checkbox');
    }
}

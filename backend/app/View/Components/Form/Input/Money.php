<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Money extends Form
{
    public $type = 'money';
    public function render()
    {
        return view('components.form.input.money');
    }
}

<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Password extends Form
{
    public $type = 'password';
    public function render()
    {
        return view('components.form.input.password');
    }
}

<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Email extends Form
{
    public $type = 'email';
    public function render()
    {
        return view('components.form.input.email');
    }
}

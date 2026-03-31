<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Textarea extends Form
{
    public $type = 'textarea';
    public function render()
    {
        return view('components.form.input.textarea');
    }
}

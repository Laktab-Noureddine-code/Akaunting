<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Text extends Form
{
    public $type = 'text';
    public function render()
    {
        return view('components.form.input.text');
    }
}

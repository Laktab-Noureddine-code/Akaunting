<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Radio extends Form
{
    public $type = 'radio';
    public function render()
    {
        return view('components.form.input.radio');
    }
}

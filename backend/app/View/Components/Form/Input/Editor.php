<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Editor extends Form
{
    public $type = 'editor';
    public function render()
    {
        return view('components.form.input.editor');
    }
}

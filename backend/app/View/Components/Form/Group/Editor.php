<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Editor extends Form
{
    public $type = 'editor';
    public function render()
    {
        return view('components.form.group.editor');
    }
}

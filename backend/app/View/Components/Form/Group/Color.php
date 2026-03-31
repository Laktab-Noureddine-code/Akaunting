<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Color extends Form
{
    public $type = 'color';
    public function render()
    {
        return view('components.form.group.color');
    }
}

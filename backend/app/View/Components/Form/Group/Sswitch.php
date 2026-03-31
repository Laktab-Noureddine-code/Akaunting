<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Sswitch extends Form
{
    public $type = 'switch';
    public function render()
    {
        return view('components.form.group.switch');
    }
}

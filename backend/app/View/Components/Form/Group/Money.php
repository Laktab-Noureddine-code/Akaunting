<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Money extends Form
{
    public $type = 'money';
    public function render()
    {
        return view('components.form.group.money');
    }
}

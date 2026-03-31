<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Password extends Form
{
    public $type = 'password';
    public function render()
    {
        return view('components.form.group.password');
    }
}

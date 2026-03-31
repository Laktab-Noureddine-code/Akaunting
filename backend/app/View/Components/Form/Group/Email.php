<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Email extends Form
{
    public $type = 'email';
    public function render()
    {
        return view('components.form.group.email');
    }
}

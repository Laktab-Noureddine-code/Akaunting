<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Time extends Form
{
    public $type = 'time';
    public function render()
    {
        return view('components.form.group.time');
    }
}

<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Date extends Form
{
    public $type = 'date';
    public function render()
    {
        return view('components.form.group.date');
    }
}

<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class DateTime extends Form
{
    public $type = 'date_time';
    public function render()
    {
        return view('components.form.group.date_time');
    }
}

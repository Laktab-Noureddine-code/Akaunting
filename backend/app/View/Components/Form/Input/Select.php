<?php
namespace App\View\Components\Form\Input;
use App\Abstracts\View\Components\Form;
class Select extends Form
{
    public $type = 'select';
    public function render()
    {
        return view('components.form.input.select');
    }
}

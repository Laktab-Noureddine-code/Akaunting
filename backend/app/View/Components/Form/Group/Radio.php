<?php
namespace App\View\Components\Form\Group;
use App\Abstracts\View\Components\Form;
class Radio extends Form
{
    public $type = 'radio';
    public $formGroupClass = 'sm:col-span-6';
    public $inputGroupClass = 'grid grid-cols-2 gap-3 sm:grid-cols-4';
    public $except = [
    ];
    public function render()
    {
        return view('components.form.group.radio');
    }
}

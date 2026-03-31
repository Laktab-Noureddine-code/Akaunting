<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class ImportViewCreated extends Event
{
    public $view;
    public function __construct($view)
    {
        $this->view = $view;
    }
}

<?php
namespace App\Events\Report;
use App\Abstracts\Event;
class GroupShowing extends Event
{
    public $class;
    public function __construct($class)
    {
        $this->class = $class;
    }
}

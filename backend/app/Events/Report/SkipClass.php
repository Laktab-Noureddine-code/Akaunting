<?php
namespace App\Events\Report;
use App\Abstracts\Event;
class SkipClass extends Event
{
    public $classes;
    public function __construct($classes)
    {
        $this->classes = $classes;
    }
}

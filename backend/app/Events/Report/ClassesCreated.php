<?php
namespace App\Events\Report;
use App\Abstracts\Event;
class ClassesCreated extends Event
{
    public $list;
    public function __construct($list)
    {
        $this->list = $list;
    }
}

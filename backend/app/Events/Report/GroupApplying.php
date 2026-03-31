<?php
namespace App\Events\Report;
use App\Abstracts\Event;
class GroupApplying extends Event
{
    public $class;
    public $model;
    public $args;
    public function __construct($class, $model, $args)
    {
        $this->class = $class;
        $this->model = $model;
        $this->args = $args;
    }
}

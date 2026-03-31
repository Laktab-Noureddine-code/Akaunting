<?php
namespace App\Events\Document;
use App\Abstracts\Event;
class PaidAmountCalculated extends Event
{
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}

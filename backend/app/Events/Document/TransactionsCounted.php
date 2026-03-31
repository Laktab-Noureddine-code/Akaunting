<?php
namespace App\Events\Document;
use App\Abstracts\Event;
class TransactionsCounted extends Event
{
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}

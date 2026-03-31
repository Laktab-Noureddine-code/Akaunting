<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class RelationshipCounting extends Event
{
    public $record;
    public function __construct($record)
    {
        $this->record = $record;
    }
}

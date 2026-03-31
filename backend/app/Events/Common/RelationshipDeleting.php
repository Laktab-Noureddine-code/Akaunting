<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class RelationshipDeleting extends Event
{
    public $record;
    public function __construct($record)
    {
        $this->record = $record;
    }
}

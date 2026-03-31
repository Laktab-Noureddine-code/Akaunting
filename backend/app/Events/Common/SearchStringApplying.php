<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class SearchStringApplying extends Event
{
    public $query;
    public function __construct($query)
    {
        $this->query = $query;
    }
}

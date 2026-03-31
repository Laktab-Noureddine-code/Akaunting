<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class GlobalSearched extends Event
{
    public $search;
    public function __construct($search)
    {
        $this->search = $search;
    }
}

<?php
namespace App\Events\Common;
use App\Abstracts\Event;
class ItemDeleting extends Event
{
    public $item;
    public function __construct($item)
    {
        $this->item = $item;
    }
}

<?php
namespace App\Events\Common;
use App\Abstracts\Event;
use App\Models\Common\Item;
class ItemUpdating extends Event
{
    public $item;
    public $request;
    public function __construct(Item $item, $request)
    {
        $this->item = $item;
        $this->request = $request;
    }
}

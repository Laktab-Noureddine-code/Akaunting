<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class CategoryDeleted extends Event
{
    public $category;
    public function __construct($category)
    {
        $this->category = $category;
    }
}

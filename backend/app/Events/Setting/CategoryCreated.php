<?php
namespace App\Events\Setting;
use App\Abstracts\Event;
class CategoryCreated extends Event
{
    public $category;
    public $request;
    public function __construct($category, $request)
    {
        $this->category = $category;
        $this->request  = $request;
    }
}

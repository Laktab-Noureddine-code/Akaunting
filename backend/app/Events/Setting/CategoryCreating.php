<?php
namespace App\Events\Setting;
use Illuminate\Queue\SerializesModels;
class CategoryCreating
{
    use SerializesModels;
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
}

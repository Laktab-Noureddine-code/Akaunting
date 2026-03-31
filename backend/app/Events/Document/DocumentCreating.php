<?php
namespace App\Events\Document;
use Illuminate\Queue\SerializesModels;
class DocumentCreating
{
    use SerializesModels;
    public $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
}

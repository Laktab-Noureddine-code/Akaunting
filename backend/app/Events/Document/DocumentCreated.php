<?php
namespace App\Events\Document;
use App\Abstracts\Event;
class DocumentCreated extends Event
{
    public $document;
    public $request;
    public function __construct($document, $request)
    {
        $this->document = $document;
        $this->request  = $request;
    }
}

<?php
namespace App\Events\Document;
use App\Abstracts\Event;
class DocumentUpdating extends Event
{
    public $document;
    public $request;
    public function __construct($document, $request)
    {
        $this->document = $document;
        $this->request  = $request;
    }
}

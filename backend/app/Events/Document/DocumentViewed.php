<?php
namespace App\Events\Document;
use App\Abstracts\Event;
class DocumentViewed extends Event
{
    public $document;
    public function __construct($document)
    {
        $this->document = $document;
    }
}

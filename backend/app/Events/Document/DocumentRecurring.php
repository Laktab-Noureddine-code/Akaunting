<?php
namespace App\Events\Document;
use App\Abstracts\Event;
use App\Models\Document\Document;
class DocumentRecurring extends Event
{
    public $document;
    public function __construct(Document $document)
    {
        $this->document     = $document;
    }
}

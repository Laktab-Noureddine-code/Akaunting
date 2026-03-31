<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
use App\Models\Document\Document;
class DocumentTransactionCreating extends Event
{
    public $document;
    public $request;
    public function __construct(Document $document, $request)
    {
        $this->document = $document;
        $this->request = $request;
    }
}

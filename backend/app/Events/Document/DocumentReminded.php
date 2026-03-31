<?php
namespace App\Events\Document;
use App\Abstracts\Event;
use App\Models\Document\Document;
class DocumentReminded extends Event
{
    public $document;
    public $notification;
    public function __construct(Document $document, string $notification)
    {
        $this->document = $document;
        $this->notification = $notification;
    }
}

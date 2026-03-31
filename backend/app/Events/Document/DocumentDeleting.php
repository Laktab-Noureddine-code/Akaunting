<?php
namespace App\Events\Document;
use App\Abstracts\Event;
class DocumentDeleting extends Event
{
    public $document;
    public function __construct($document)
    {
        $this->document = $document;
    }
}

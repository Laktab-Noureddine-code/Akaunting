<?php
namespace App\Events\Document;
use Illuminate\Queue\SerializesModels;
class DocumentPrinting
{
    use SerializesModels;
    public $document;
    public function __construct($document)
    {
        $this->document = $document;
    }
}

<?php
namespace App\Events\Document;
use Illuminate\Queue\SerializesModels;
class DocumentReceived
{
    use SerializesModels;
    public $document;
    public function __construct($document)
    {
        $this->document = $document;
    }
}

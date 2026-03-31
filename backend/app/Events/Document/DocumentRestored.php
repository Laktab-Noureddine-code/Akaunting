<?php
namespace App\Events\Document;
use Illuminate\Queue\SerializesModels;
class DocumentRestored
{
    use SerializesModels;
    public $document;
    public function __construct($document)
    {
        $this->document = $document;
    }
}

<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
class DocumentTransactionCreated extends Event
{
    public $document;
    public $transaction;
    public function __construct(Document $document, Transaction $transaction)
    {
        $this->document = $document;
        $this->transaction = $transaction;
    }
}

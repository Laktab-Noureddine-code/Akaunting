<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class TransactionDeleted extends Event
{
    public $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
}

<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class TransactionSending extends Event
{
    public $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
}

<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class TransactionSent extends Event
{
    public $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
}

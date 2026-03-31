<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class TransactionRecurring extends Event
{
    public $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
}

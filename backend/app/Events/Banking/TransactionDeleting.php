<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class TransactionDeleting extends Event
{
    public $transaction;
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
}

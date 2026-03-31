<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
use App\Models\Banking\Transaction;
class TransactionUpdated extends Event
{
    public $transaction;
    public $request;
    public function __construct(Transaction $transaction, $request)
    {
        $this->transaction = $transaction;
        $this->request  = $request;
    }
}

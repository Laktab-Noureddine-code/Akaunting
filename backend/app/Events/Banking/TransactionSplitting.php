<?php
namespace App\Events\Banking;
use App\Abstracts\Event;
class TransactionSplitting extends Event
{
    public $request;
    public $transaction;
    public function __construct($request, $transaction)
    {
        $this->request = $request;
        $this->transaction = $transaction;
    }
}

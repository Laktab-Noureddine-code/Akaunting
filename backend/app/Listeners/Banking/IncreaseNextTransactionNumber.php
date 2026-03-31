<?php
namespace App\Listeners\Banking;
use App\Events\Banking\TransactionCreated as Event;
use App\Traits\Transactions;
class IncreaseNextTransactionNumber
{
    use Transactions;
    public function handle(Event $event)
    {
        $suffix = $event->transaction->isRecurringTransaction() ? '-recurring' : '';
        $this->increaseNextTransactionNumber($event->transaction->type, $suffix);
    }
}

<?php
namespace App\Listeners\Document;
use App\Events\Document\PaymentReceived as Event;
use App\Notifications\Portal\PaymentReceived as Notification;
class SendDocumentPaymentNotification
{
    public function handle(Event $event)
    {
        if ($event->request['type'] !== 'income') {
            return;
        }
        $document = $event->document;
        $transaction = $document->transactions()->latest()->first();
        if (! $transaction) {
            return;
        }
        if ($document->contact && !empty($document->contact_email)) {
            $document->contact->notify(new Notification($document, $transaction, "{$document->type}_payment_customer"), true);
        }
        foreach ($document->company->users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }
            $user->notify(new Notification($document, $transaction, "{$document->type}_payment_admin"));
        }
    }
}

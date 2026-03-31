<?php
namespace App\Listeners\Document;
use App\Events\Document\DocumentRecurring as Event;
use App\Events\Document\DocumentSent;
use App\Traits\Documents;
class SendDocumentRecurringNotification
{
    use Documents;
    public function handle(Event $event)
    {
        $document = $event->document;
        $config = config('type.document.' . $document->type . '.notification');
        if (empty($config) || empty($config['class'])) {
            return;
        }
        if ($document->parent?->recurring?->auto_send == false) {
            return;
        }
        $notification = $config['class'];
        $attach_pdf = true;
        if ($this->canNotifyTheContactOfDocument($document)) {
            $document->contact->notify(new $notification($document, "{$document->type}_recur_customer", $attach_pdf));
        }
        $sent = config('type.document.' . $document->type . '.auto_send', DocumentSent::class);
        event(new $sent($document));
        if (! $config['notify_user']) {
            return;
        }
        foreach ($document->company->users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }
            $user->notify(new $notification($document, "{$document->type}_recur_admin"));
        }
    }
}

<?php
namespace App\Listeners\Document;
use App\Events\Document\DocumentCreated as Event;
use App\Traits\Documents;
class IncreaseNextDocumentNumber
{
    use Documents;
    public function handle(Event $event)
    {
        $this->increaseNextDocumentNumber($event->document->type);
    }
}

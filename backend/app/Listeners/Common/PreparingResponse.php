<?php
namespace App\Listeners\Common;
use Illuminate\Routing\Events\PreparingResponse as Event;
class PreparingResponse
{
    public function handle(Event $event): void
    {
        if (! $event->response instanceof \Illuminate\View\View) {
            return;
        }
        $data = $event->response->getData();
        foreach (['currencies', 'currency'] as $key) {
            if (! isset($data[$key])) {
                continue;
            }
            $value = $data[$key] ?? null;
            view()->share($key, $value);
        }
    }
}

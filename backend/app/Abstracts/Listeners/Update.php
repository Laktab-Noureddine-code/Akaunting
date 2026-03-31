<?php
namespace App\Abstracts\Listeners;
use App\Utilities\Versions;
abstract class Update
{
    const ALIAS = '';
    const VERSION = '';
    public function skipThisUpdate($event)
    {
        if ($event->alias != static::ALIAS) {
            return true;
        }
        return ! Versions::shouldUpdate(static::VERSION, $event->old, $event->new);
    }
    public function check($event)
    {
        return !$this->skipThisUpdate($event);
    }
}

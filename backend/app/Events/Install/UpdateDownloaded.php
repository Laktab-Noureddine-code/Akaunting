<?php
namespace App\Events\Install;
use App\Abstracts\Event;
class UpdateDownloaded extends Event
{
    public $alias;
    public $old;
    public $new;
    public function __construct($alias, $old, $new)
    {
        $this->alias = $alias;
        $this->old = $old;
        $this->new = $new;
    }
}

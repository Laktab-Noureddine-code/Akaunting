<?php
namespace App\Listeners\Module;
use App\Events\Module\Installed as Event;
use App\Jobs\Install\DownloadModule;
use App\Jobs\Install\InstallModule;
use App\Traits\Jobs;
use App\Traits\Modules;
use Illuminate\Support\Facades\App;
class InstallExtraModules
{
    use Jobs, Modules;
    public function handle(Event $event)
    {
        if (App::environment('testing')) {
            return;
        }
        if ($event->alias == 'core') {
            return;
        }
        $extra_modules = module($event->alias)->get('extra-modules');
        if (empty($extra_modules)) {
            return;
        }
        foreach ($extra_modules as $alias => $level) {
            if ($level != 'required') {
                continue;
            }
            if ($this->moduleIsEnabled($alias)) {
                continue;
            }
            try {
                if (!$this->moduleExists($alias)) {
                    $this->dispatch(new DownloadModule($alias, $event->company_id));
                }
                $this->dispatch(new InstallModule($alias, $event->company_id, $event->locale));
            } catch (\Exception $e) {
                report($e);
                return false;
            }
        }
    }
}

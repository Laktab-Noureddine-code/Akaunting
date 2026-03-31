<?php
namespace App\Listeners\Module;
use App\Events\Install\UpdateFinished as Event;
use App\Utilities\Console;
use App\Utilities\Versions;
use Illuminate\Support\Facades\App;
class UpdateExtraModules
{
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
            $extra_module = module($alias);
            if (empty($extra_module)) {
                continue;
            }
            $installed_version = $extra_module->get('version');
            $latest_version = Versions::latest($alias)?->latest;
            if (version_compare($installed_version, $latest_version, '>=')) {
                continue;
            }
            $company_id = company_id();
            $command = "update {$alias} {$company_id} {$latest_version}";
            if (true !== $result = Console::run($command)) {
                $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $alias]);
                report($message);
                return false;
            }
        }
    }
}

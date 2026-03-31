<?php
namespace App\Console\Commands;
use App\Abstracts\Commands\Module as Command;
use App\Events\Module\Uninstalled;
class UninstallModule extends Command
{
    protected $signature = 'module:uninstall {alias} {company} {locale=en-GB}';
    protected $description = 'Uninstall the specified module.';
    public function handle()
    {
        $this->prepare();
        if (!$this->getModel()) {
            $this->info("Module [{$this->alias}] not found.");
            return;
        }
        $this->changeRuntime();
        $this->model->delete();
        $this->createHistory('uninstalled');
        event(new Uninstalled($this->alias, $this->company_id));
        module($this->alias)->delete();
        $this->revertRuntime();
        $this->info("Module [{$this->alias}] uninstalled.");
    }
}

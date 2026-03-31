<?php
namespace App\Console\Commands;
use App\Utilities\Console;
use Illuminate\Console\Command;
class UpdateAll extends Command
{
    protected $signature = 'update:all {company=1}';
    protected $description = 'Allows to update Akaunting and all modules at once';
    public function handle()
    {
        set_time_limit(0); 
        $this->info('Starting update...');
        if ($this->runUpdate('core') !== true) {
            $this->error('Not able to update core!');
            return;
        }
        $modules = module()->all();
        foreach ($modules as $module) {
            $alias = $module->get('alias');
            if ($this->runUpdate($alias) !== true) {
                $this->error('Not able to update ' . $alias . '!');
            }
        }
        $this->info('Update finished.');
    }
    protected function runUpdate($alias)
    {
        $this->info('Updating ' . $alias . '...');
        $company_id = $this->argument('company');
        $command = "update {$alias} {$company_id}";
        if (true !== $result = Console::run($command)) {
            $message = !empty($result) ? $result : trans('modules.errors.finish', ['module' => $alias]);
            $this->error($message);
            return false;
        }
        return true;
    }
}

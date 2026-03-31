<?php
namespace App\Console\Commands;
use App\Events\Install\UpdateFinished;
use Illuminate\Console\Command;
class UpdateDb extends Command
{
    protected $signature = 'update:db {alias=core} {company=1} {new=3.0.0} {old=2.1.36}';
    protected $description = 'Allows to update Akaunting database manually';
    public function handle()
    {
        $alias = $this->argument('alias');
        $company_id = $this->argument('company');
        $new = $this->argument('new');
        $old = $this->argument('old');
        company($company_id)->makeCurrent();
        config(['laravel-model-caching.enabled' => false]);
        event(new UpdateFinished($alias, $new, $old));
    }
}

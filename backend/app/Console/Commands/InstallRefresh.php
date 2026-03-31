<?php
namespace App\Console\Commands;
use App\Models\Common\Company;
use Illuminate\Console\Command;
class InstallRefresh extends Command
{
    protected $signature = 'install:refresh {--admin-password=123456}';
    protected $description = 'Allows to refresh Akaunting installation directly through CLI';
    public function handle()
    {
        $user = user_model_class()::first();
        $company = Company::first();
        $this->info('Resetting migrations');
        $this->callSilent('migrate:reset', [
            '--force' => true,
        ]);
        $this->info('Installing Akaunting');
        $this->callSilent('install', [
            '--db-host' => env('DB_HOST'),
            '--db-port' => env('DB_PORT'),
            '--db-name' => env('DB_DATABASE'),
            '--db-username' => env('DB_USERNAME'),
            '--db-password' => env('DB_PASSWORD'),
            '--db-prefix' => env('DB_PREFIX'),
            '--company-name' => $company->name,
            '--company-email' => $company->email,
            '--admin-email' => $user->email,
            '--admin-password' => $this->option('admin-password'),
            '--locale' => $company->locale,
            '--no-interaction' => true,
        ]);
        $this->info('Installation refreshed');
    }
}

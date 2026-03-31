<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
class UserSeed extends Command
{
    protected $signature = 'user:seed {user} {company}';
    protected $description = 'Seed for specific user';
    public function handle()
    {
        $class = $this->laravel->make('Database\Seeds\User');
        $class->setContainer($this->laravel)->setCommand($this)->__invoke();
    }
}

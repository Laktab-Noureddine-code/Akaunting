<?php
namespace App\Console\Commands;
use Database\Seeds\SampleData as SampleDataSeeder;
use Illuminate\Console\Command;
class SampleData extends Command
{
    protected $signature = 'sample-data:seed {--count=100 : total records for each item} {--company=1 : the company id}';
    protected $description = 'Seed for sample data';
    public function handle()
    {
        $class = $this->laravel->make(SampleDataSeeder::class);
        $seeder = $class->setContainer($this->laravel)->setCommand($this);
        $seeder->__invoke();
    }
}

<?php
namespace Database\Seeds;
use Illuminate\Database\Seeder;
class Company extends Seeder
{
    public function run()
    {
        $this->call(Accounts::class);
        $this->call(Categories::class);
        $this->call(Currencies::class);
        $this->call(EmailTemplates::class);
        $this->call(Modules::class);
        $this->call(Reports::class);
        $this->call(Settings::class);
    }
}

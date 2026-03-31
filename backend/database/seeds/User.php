<?php
namespace Database\Seeds;
use Illuminate\Database\Seeder;
class User extends Seeder
{
    public function run()
    {
        $this->call(Dashboards::class);
    }
}

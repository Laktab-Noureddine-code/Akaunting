<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider as Provider;
class Broadcast extends Provider
{
    public function boot()
    {
        Broadcast::routes();
        require base_path('routes/channels.php');
    }
}

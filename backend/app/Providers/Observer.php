<?php
namespace App\Providers;
use App\Models\Banking\Transaction;
use Illuminate\Support\ServiceProvider as Provider;
class Observer extends Provider
{
    public function register()
    {
    }
    public function boot()
    {
        Transaction::observe('App\Observers\Transaction');
    }
}

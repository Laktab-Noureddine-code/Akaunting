<?php
namespace App\Providers;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as Provider;
class Auth extends Provider
{
    protected $policies = [
    ];
    public function boot()
    {
        $this->registerPolicies();
    }
}

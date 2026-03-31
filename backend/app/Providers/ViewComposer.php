<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Support\Facades\View;
class ViewComposer extends Provider
{
    public function boot()
    {
        View::composer(
            ['contacts.*'],
            'App\Http\ViewComposers\ContactType'
        );
        View::composer(
            ['documents.*', 'portal.documents.*'],
            'App\Http\ViewComposers\DocumentType'
        );
        View::composer(
            ['components.documents.form.metadata'],
            'App\Http\ViewComposers\DocumentRecurring'
        );
        View::composer(
            ['components.layouts.admin.notifications'],
            'App\Http\ViewComposers\ReadOnlyNotification'
        );
        View::composer(
            ['components.layouts.admin.header'],
            'App\Http\ViewComposers\PlanLimits'
        );
    }
    public function register()
    {
    }
}

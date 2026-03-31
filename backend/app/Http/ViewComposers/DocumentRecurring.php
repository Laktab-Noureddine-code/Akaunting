<?php
namespace App\Http\ViewComposers;
use Illuminate\View\View;
use Illuminate\Support\Str;
class DocumentRecurring
{
    public function compose(View $view)
    {
        $route = request()->route();
        if (empty($route)) {
            return;
        }
        $controller = $route->getController();
        $type = $controller->type ?? '';
        if (! Str::contains($type, 'recurring')) {
            return;
        }
        $view->with([
            'type' => $type,
        ]);
        $view->setPath(view('components.documents.form.recurring_metadata', compact('type'))->getPath());
    }
}

<?php
namespace App\View\Components\Index;
use App\Abstracts\View\Component;
class Search extends Component
{
    public $searchString;
    public $bulkAction;
    public $action;
    public $route;
    public $url;
    public function __construct(
        $searchString = false, $bulkAction = false, $action = false, $route = false, $url = false
    ) {
        $this->searchString = $searchString;
        $this->bulkAction = $bulkAction;
        $this->action = $this->getAction($action, $route, $url);
    }
    public function render()
    {
        return view('components.index.search');
    }
    protected function getAction($action, $route, $url)
    {
        if (! empty($action)) {
            return $action;
        }
        if (! empty($route)) {
            return $this->getRouteAction($route);
        }
        if (! empty($url)) {
            return $this->getUrlAction($url);
        }
        return '';
    }
    protected function getUrlAction($options)
    {
        if (is_array($options)) {
            return url($options[0], array_slice($options, 1));
        }
        return url($options);
    }
    protected function getRouteAction($options)
    {
        if (is_array($options)) {
            $parameters = array_slice($options, 1);
            if (array_keys($options) === [0, 1]) {
                $parameters = head($parameters);
            }
            return route($options[0], $parameters);
        }
        return route($options);
    }
}

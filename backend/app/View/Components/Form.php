<?php
namespace App\View\Components;
use Illuminate\Support\Str;
use App\Abstracts\View\Components\Form as BaseForm;
class Form extends BaseForm
{
    public $method;
    public $action;
    public $model;
    public $class;
    public $role;
    public $novalidate;
    public $enctype;
    public $acceptCharset;
    public $route;
    public $url;
    public $submit;
    public function __construct(
        string $method = 'POST',
        string $action = '',
        $model = false,
        string $class = 'mb-0',
        string $role = 'form',
        string $novalidate = 'true',
        string $enctype = 'multipart/form-data',
        string $acceptCharset = 'UTF-8',
        $route = '',
        $url = '',
        $submit = 'onSubmit'
    ) {
        $this->method = Str::upper($method);
        $this->action = $this->getAction($action, $route, $url);
        $this->model = $model;
        $this->class = $class;
        $this->role = $role;
        $this->novalidate = $novalidate;
        $this->enctype = $enctype;
        $this->acceptCharset = $acceptCharset;
        $this->submit = $submit;
    }
    public function render()
    {
        return view('components.form.index');
    }
    protected function getAction($action, $route, $url)
    {
        if (!empty($action)) {
            return $action;
        }
        if (!empty($route)) {
            return $this->getRouteAction($route);
        }
        if (!empty($url)) {
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

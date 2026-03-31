<?php
namespace App\View\Components;
use App\Abstracts\View\Component;
class Alert extends Component
{
    public $rounded;
    public $border;
    public $color;
    public $icon;
    public $typr;
    public $title;
    public $description;
    public $message;
    public $list;
    public $actions;
    public $dismiss;
    public function __construct(
        bool $rounded = true, bool $border = false,
        string $color = '', string $icon = '',
        string $type = '',
        string $title = '', string $description = '', string $message = '',
        array $list = [], array $actions = [],
        bool $dismiss = false
    ) {
        $this->rounded = $rounded;
        $this->border = $border;
        $this->type = $type;
        $this->color = $this->getColor($color);
        $this->icon = $this->getIcon($icon);
        $this->title = $title;
        $this->description = $description;
        $this->message = $message;
        $this->list = $list;
        $this->actions = $actions;
        $this->dismiss = $dismiss;
    }
    public function render()
    {
        return view('components.alert.index');
    }
    protected function getColor($color)
    {
        if (! empty($color)) {
            return $color;
        }
        switch ($this->type) {
            case 'success':
                $color = 'green';
                break;
            case 'info':
                $color = 'blue';
                break;
            case 'warning':
                $color = 'orange';
                break;
            case 'danger':
            case 'error':
                $color = 'red';
                break;
            default:
                $color = 'green';
                break;
        }
        return $color;
    }
    protected function getIcon($icon)
    {
        if (! empty($icon)) {
            return $icon;
        }
        switch ($this->type) {
            case 'success':
                $icon = 'check_circle';
                break;
            case 'info':
                $icon = 'info';
                break;
            case 'warning':
                $icon = 'warning';
                break;
            case 'danger':
            case 'error':
                $icon = 'error';
                break;
            default:
                $icon = 'check_circle';
                break;
        }
        return $icon;
    }
}

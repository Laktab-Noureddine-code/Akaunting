<?php
namespace App\View\Components\Table;
use App\Abstracts\View\Component;
class Actions extends Component
{
    public $model;
    public $actions;
    public function __construct(
        $model = false,
        array $actions = []
    ) {
        $this->model = $model;
        $this->actions = $this->getActions($actions);
    }
    public function render()
    {
        return view('components.table.actions');
    }
    protected function getActions($actions)
    {
        if (empty($actions)) {
            $actions = [];
            if ($this->model && ! empty($this->model->line_actions)) {
                $actions = $this->model->line_actions;
            }
        }
        foreach ($actions as $key => $action) {
            $attributes = [];
            if (! empty($action['attributes'])) {
                $attributes = $action['attributes'];
            }
            $actions[$key]['attributes'] = $this->getAttributes($attributes);
        }
        return $actions;
    }
    public function getAttributes($attributes)
    {
        $html = [];
        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);
            if (! is_null($element)) {
                $html[] = $element;
            }
        }
        return count($html) > 0 ? ' ' . implode(' ', $html) : '';
    }
    protected function attributeElement($key, $value)
    {
        if (is_numeric($key)) {
            return $value;
        }
        if (is_bool($value) && $key !== 'value') {
            return $value ? $key : '';
        }
        if (is_array($value) && $key === 'class') {
            return 'class=' . implode(' ', $value);
        }
        if (! is_null($value)) {
            return $key . '=' . e($value, false);
        }
    }
}

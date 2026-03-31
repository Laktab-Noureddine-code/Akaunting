<?php
namespace App\View\Components\Index;
use App\Abstracts\View\Component;
use Illuminate\Support\Str;
class Category extends Component
{
    public $model;
    public $name;
    public $backgroundColor;
    public $backgroundStyle;
    public $textColor;
    public function __construct(
        $model = false, $name = '', $backgroundColor = '', $textColor = ''
    ) {
        $this->model = $model;
        $this->name = $this->getName($name, $model);
        $this->backgroundColor = $this->getBackgroundColor($backgroundColor, $model);
        $this->textColor = $this->getTextColor($textColor, $this->backgroundColor);
    }
    public function render()
    {
        return view('components.index.category');
    }
    protected function getName($name, $model)
    {
        if (! empty($name)) {
            return $name;
        }
        if (! empty($model)) {
            $name = $model->name;
        }
        return $name;
    }
    protected function getBackgroundColor($backgroundColor, $model)
    {
        if (! empty($backgroundColor)) {
            return $backgroundColor;
        }
        if (! empty($model)) {
            if (Str::contains($model->color, ['
                $this->backgroundStyle = $model->color;
                return $backgroundColor;
            }
            $backgroundColor = 'bg-' . $model->color;
        }
        return $backgroundColor;
    }
    protected function getTextColor($textColor, $backgroundColor)
    {
        if (! empty($textColor)) {
            return $textColor;
        }
        if (! empty($backgroundColor)) {
            $x = explode('-', $backgroundColor);
            $textColor = 'text-black';
            if ($x[1] >= 500) {
               $textColor = 'text-white';
            }
        }
        return $textColor;
    }
}

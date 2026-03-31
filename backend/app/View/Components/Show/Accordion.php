<?php
namespace App\View\Components\Show;
use App\Abstracts\View\Component;
class Accordion extends Component
{
    public $type;
    public $icon;
    public $open;
    public function __construct(
        string $type, string $icon = '', bool $open = false
    ) {
        $this->type = $type;
        $this->icon = $this->getIcon($icon);
        $this->open = $open;
    }
    public function render()
    {
        return view('components.show.accordion.index');
    }
    protected function getIcon($icon)
    {
        if (! empty($icon)) {
            return $icon;
        }
        return 'expand_more';
    }
}

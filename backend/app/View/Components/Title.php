<?php
namespace App\View\Components;
use App\Abstracts\View\Component;
class Title extends Component
{
    public $textSize;
    public function __construct(
      string $textSize = '',
    ) {
      $this->textSize = $this->getTextSize($textSize);
    }
    public function render()
    {
        return view('components.title');
    }
    protected function getTextSize($textSize)
    {
        switch ($textSize) {
            case 'short':
                $textSize = '15';
            break;
            default:
                $textSize = '25';
            break;
        }
        return $textSize;
    }
}

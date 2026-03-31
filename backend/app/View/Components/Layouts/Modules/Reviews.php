<?php
namespace App\View\Components\Layouts\Modules;
use App\Abstracts\View\Component;
class Reviews extends Component
{
    public $reviews;
    public function __construct(
        $reviews = []
    ) {
        $this->reviews = $reviews;
    }
    public function render()
    {
        return view('components.layouts.modules.reviews');
    }
}

<?php
namespace App\View\Components\Documents\Show;
use App\Abstracts\View\Component;
class Message extends Component
{
    public $type;
    public $backgroundColor;
    public $textColor;
    public $message;
    public function __construct(
        string $type = '', string $backgroundColor = '', string $textColor = '', string $message = ''
    ) {
        $this->type = $type;
        $this->backgroundColor = $backgroundColor;
        $this->textColor = $textColor;
        $this->message = $message;
    }
    public function render()
    {
        return view('components.documents.show.message');
    }
}

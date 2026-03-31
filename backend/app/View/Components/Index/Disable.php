<?php
namespace App\View\Components\Index;
use App\Abstracts\View\Component;
use Illuminate\Support\Str;
class Disable extends Component
{
    public $id;
    public $position;
    public $icon;
    public $iconType;
    public $text;
    public $disableText;
    public function __construct(
        $position = 'right', $icon = 'unpublished', $iconType = '-round', $text = '', $disableText = ''
    ) {
        $this->id = 'tooltip-disable-' . mt_rand(1, time());
        $this->position = $position;
        $this->icon = $icon;
        $this->iconType = $iconType;
        $this->disableText = $this->getDisableText($text, $disableText);
    }
    public function render()
    {
        return view('components.index.disable');
    }
    protected function getDisableText($text, $disableText)
    {
        if (! empty($disableText)) {
            return $disableText;
        }
        return trans('general.disabled_type', ['type' => Str::lower($text)]);
    }
}

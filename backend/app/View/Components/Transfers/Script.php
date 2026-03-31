<?php
namespace App\View\Components\Transfers;
use App\Abstracts\View\Component;
use App\Traits\ViewComponents;
class Script extends Component
{
    use ViewComponents;
    public const OBJECT_TYPE = 'transfer';
    public const DEFAULT_TYPE = 'transfer';
    public const DEFAULT_PLURAL_TYPE = 'transfers';
    public $model;
    public $transfer;
    public $alias;
    public $folder;
    public $file;
    public function __construct(
        $model = false, $transfer = false,
        string $alias = '', string $folder = '', string $file = ''
    ) {
        $this->model = ! empty($model) ? $model : $transfer;
        $this->transfer = ! empty($model) ? $model : $transfer;
        $this->alias = $this->getAlias($type, $alias);
        $this->folder = $this->getScriptFolder($type, $folder);
        $this->file = $this->getScriptFile($type, $file);
    }
    public function render()
    {
        return view('components.transfers.script');
    }
}

<?php
namespace App\View\Components\Transactions;
use App\Abstracts\View\Component;
use App\Traits\ViewComponents;
class Script extends Component
{
    use ViewComponents;
    public const OBJECT_TYPE = 'transaction';
    public const DEFAULT_TYPE = 'income';
    public const DEFAULT_PLURAL_TYPE = 'incomes';
    public $type;
    public $transaction;
    public $alias;
    public $folder;
    public $file;
    public function __construct(
        string $type = '', $transaction = false,
        string $alias = '', string $folder = '', string $file = ''
    ) {
        $this->type = $type;
        $this->transaction = $transaction;
        $this->alias = $this->getAlias($type, $alias);
        $this->folder = $this->getScriptFolder($type, $folder);
        $this->file = $this->getScriptFile($type, $file);
    }
    public function render()
    {
        return view('components.transactions.script');
    }
}

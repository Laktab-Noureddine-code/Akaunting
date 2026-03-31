<?php
namespace App\View\Components;
use App\Abstracts\View\Component;
class Script extends Component
{
    public $alias;
    public $folder;
    public $file;
    public $source;
    public function __construct(
        string $alias = 'core', string $folder = '', string $file = ''
    ) {
        $this->alias = $alias;
        $this->folder = $folder;
        $this->file = $file;
        $this->source = $this->getSource($alias, $folder, $file);
    }
    public function render()
    {
        return view('components.script');
    }
    protected function getSource($alias, $folder, $file)
    {
        $path = 'public/js/';
        $version = version('short');
        if ($alias != 'core') {
            try {
                $module = module($alias);
                if ($module) {
                    $path = 'modules/' . $module->getStudlyName() . '/Resources/assets/js/';
                    $version = module_version($alias);
                }
            } catch (\Exception $e) {
            }
        }
        if (! empty($folder)) {
            $path .= $folder . '/';
        }
        $path .= $file . '.min.js?v=' . $version;
        return $path;
    }
}

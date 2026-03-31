<?php
namespace App\Jobs\Install;
use App\Abstracts\Job;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class CopyFiles extends Job
{
    protected $alias;
    protected $path;
    public function __construct($alias, $path)
    {
        $this->alias = $alias;
        $this->path = $path;
    }
    public function handle()
    {
        if (empty($this->path)) {
            throw new \Exception(trans('modules.errors.file_copy', ['module' => $this->alias]));
        }
        $source = storage_path('app/temp/' . $this->path);
        $destination = $this->getDestination($source);
        if (!File::copyDirectory($source, $destination)) {
            throw new \Exception(trans('modules.errors.file_copy', ['module' => $this->alias]));
        }
        File::deleteDirectory($source);
    }
    protected function getDestination($source)
    {
        if ($this->alias == 'core') {
            return base_path();
        }
        if (!is_file($source . '/module.json')) {
            throw new \Exception(trans('modules.errors.file_copy', ['module' => $this->alias]));
        }
        $modules_path = config('module.paths.modules');
        if (!File::isDirectory($modules_path)) {
            File::makeDirectory($modules_path);
        }
        $module_path = $modules_path . '/' . Str::studly($this->alias);
        if (!File::isDirectory($module_path)) {
            File::makeDirectory($module_path);
        }
        return $module_path;
    }
}

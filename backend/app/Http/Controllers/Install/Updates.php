<?php
namespace App\Http\Controllers\Install;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Module\Install as InstallRequest;
use App\Events\Install\UpdateCacheCleared;
use App\Events\Install\UpdateCopied;
use App\Events\Install\UpdateDownloaded;
use App\Events\Install\UpdateUnzipped;
use App\Jobs\Install\CopyFiles;
use App\Jobs\Install\DownloadFile;
use App\Jobs\Install\FinishUpdate;
use App\Jobs\Install\UnzipFile;
use App\Utilities\Versions;
use Illuminate\Support\Facades\Cache;
class Updates extends Controller
{
    public function index()
    {
        $updates = Versions::getUpdates();
        $core = null;
        $modules = [];
        if (isset($updates['core'])) {
            $core = $updates['core'];
        }
        $rows = module()->all();
        if ($rows) {
            foreach ($rows as $row) {
                $alias = $row->get('alias');
                if (!isset($updates[$alias])) {
                    continue;
                }
                $m = new \stdClass();
                $m->name = $row->getName();
                $m->alias = $row->get('alias');
                $m->installed = $row->get('version');
                $m->latest = $updates[$alias]->latest;
                $m->errors = $updates[$alias]->errors;
                $m->message = $updates[$alias]->message;
                $modules[] = $m;
            }
        }
        return view('install.updates.index', compact('core', 'modules'));
    }
    public function changelog()
    {
        return Versions::changelog();
    }
    public function check()
    {
        Cache::forget('updates');
        Cache::forget('versions');
        event(new UpdateCacheCleared(company_id()));
        return redirect()->back();
    }
    public function run($alias, $version)
    {
        if ($alias == 'core') {
            $name = 'Akaunting ' . $version;
            $installed = version('short');
        } else {
            $module = module($alias);
            $name = $module->getName();
            $installed = $module->get('version');
            if (version_compare($installed, $version, '>=')) {
                flash(trans('modules.warning.latest_version', ['module' => $name]))->warning()->important();
                return $this->check();
            }
        }
        return view('install.updates.edit', compact('alias', 'name', 'installed', 'version'));
    }
    public function steps(InstallRequest $request)
    {
        $steps = [];
        $name = $request['name'];
        $steps[] = [
            'text' => trans('modules.installation.download', ['module' => $name]),
            'url'  => route('updates.download'),
        ];
        $steps[] = [
            'text' => trans('modules.installation.unzip', ['module' => $name]),
            'url'  => route('updates.unzip'),
        ];
        $steps[] = [
            'text' => trans('modules.installation.file_copy', ['module' => $name]),
            'url'  => route('updates.copy'),
        ];
        $steps[] = [
            'text' => trans('modules.installation.finish', ['module' => $name]),
            'url'  => route('updates.finish'),
        ];
        $steps[] = [
            'text' => trans('modules.installation.redirect', ['module' => $name]),
            'url'  => route('updates.redirect'),
        ];
        return response()->json([
            'success' => true,
            'error' => false,
            'data' => $steps,
            'message' => null
        ]);
    }
    public function download(InstallRequest $request)
    {
        set_time_limit(900); 
        try {
            $path = $this->dispatch(new DownloadFile($request['alias'], $request['version']));
            event(new UpdateDownloaded($request['alias'], $request['version'], $request['installed']));
            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'path' => $path,
                ],
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }
        return response()->json($json);
    }
    public function unzip(InstallRequest $request)
    {
        set_time_limit(900); 
        try {
            $path = $this->dispatch(new UnzipFile($request['alias'], $request['path']));
            event(new UpdateUnzipped($request['alias'], $request['version'], $request['installed']));
            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'path' => $path,
                ],
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }
        return response()->json($json);
    }
    public function copyFiles(InstallRequest $request)
    {
        set_time_limit(900); 
        try {
            $path = $this->dispatch(new CopyFiles($request['alias'], $request['path']));
            event(new UpdateCopied($request['alias'], $request['version'], $request['installed']));
            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [
                    'path' => $path,
                ],
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }
        return response()->json($json);
    }
    public function finish(InstallRequest $request)
    {
        set_time_limit(900); 
        try {
            $this->dispatch(new FinishUpdate($request['alias'], $request['version'], $request['installed'], company_id()));
            $json = [
                'success' => true,
                'error' => false,
                'message' => null,
                'data' => [],
            ];
        } catch (\Exception $e) {
            $json = [
                'success' => false,
                'error' => true,
                'message' => $e->getMessage(),
                'data' => [],
            ];
        }
        return response()->json($json);
    }
    public function redirect()
    {
        $json = [
            'success' => true,
            'errors' => false,
            'redirect' => route('updates.index'),
            'data' => [],
        ];
        return response()->json($json);
    }
}

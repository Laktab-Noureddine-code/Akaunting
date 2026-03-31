<?php
namespace App\Http\Controllers\Api\Settings;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Setting as Request;
use App\Http\Resources\Setting\Setting as Resource;
use App\Models\Setting\Setting;
use Laratrust\Middleware\LaratrustMiddleware;
class Settings extends ApiController
{
    public function __construct()
    {
        $this->middleware('permission:create-settings-company|create-settings-defaults|create-settings-localisation')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-company|read-settings-defaults|read-settings-localisation')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-company|update-settings-defaults|update-settings-localisation')->only('update', 'enable', 'disable', 'destroy');
    }
    public function index()
    {
        $settings = Setting::all();
        return Resource::collection($settings);
    }
    public function show($id)
    {
        if (is_numeric($id)) {
            $setting = Setting::find($id);
        } else {
            $setting = Setting::where('key', $id)->first();
        }
        if (! $setting instanceof Setting) {
            return $this->errorInternal('No query results for model [' . Setting::class . '] ' . $id);
        }
        return new Resource($setting);
    }
    public function store(Request $request)
    {
        try {
            $setting = Setting::create($request->all());
            return $this->created(route('api.settings.show', $setting->id), new Resource($setting));
        } catch (\Exception $e) {
            $this->errorInternal($e->getMessage());
        }
    }
    public function update(Setting $setting, Request $request)
    {
        try {
            $setting->update($request->all());
            return new Resource($setting->fresh());
        } catch (\Exception $e) {
            $this->errorInternal($e->getMessage());
        }
    }
    public function destroy(Setting $setting)
    {
        try {
            $setting->delete();
            return $this->noContent();
        } catch (\Exception $e) {
            $this->errorInternal($e->getMessage());
        }
    }
}

<?php
namespace App\Providers;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route as Facade;
class Route extends Provider
{
    public const HOME = '/';
    protected $namespace = 'App\Http\Controllers';
    public function register()
    {
        parent::register();
        Facade::macro('module', function ($alias, $routes, $attrs) {
            $attributes = [
                'middleware' => $attrs['middleware'],
            ];
            if (isset($attrs['namespace'])) {
                if (!is_null($attrs['namespace'])) {
                    $attributes['namespace'] = $attrs['namespace'];
                }
            } else {
                $attributes['namespace'] = 'Modules\\' . module($alias)->getStudlyName() . '\Http\Controllers';
            }
            if (isset($attrs['prefix'])) {
                if (!is_null($attrs['prefix'])) {
                    $attributes['prefix'] = '{company_id}/' . $attrs['prefix'];
                }
            } else {
                $attributes['prefix'] = '{company_id}/' . $alias;
            }
            if (isset($attrs['as'])) {
                if (!is_null($attrs['as'])) {
                    $attributes['as'] = $attrs['as'];
                }
            } else {
                $attributes['as'] = $alias . '.';
            }
            return Facade::group($attributes, $routes);
        });
        Facade::macro('admin', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'middleware'    => 'admin',
            ], $attributes));
        });
        Facade::macro('preview', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'middleware'    => 'preview',
                'prefix'        => 'preview/' . $alias,
                'as'            => 'preview.' . $alias . '.',
            ], $attributes));
        });
        Facade::macro('portal', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'middleware'    => 'portal',
                'prefix'        => 'portal/' . $alias,
                'as'            => 'portal.' . $alias . '.',
            ], $attributes));
        });
        Facade::macro('signed', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'middleware'    => 'signed',
                'prefix'        => 'signed/' . $alias,
                'as'            => 'signed.' . $alias . '.',
            ], $attributes));
        });
        Facade::macro('api', function ($alias, $routes, $attributes = []) {
            return Facade::module($alias, $routes, array_merge([
                'namespace'     => 'Modules\\' . module($alias)->getStudlyName() . '\Http\Controllers\Api',
                'domain'        => config('api.domain'),
                'middleware'    => config('api.middleware'),
                'prefix'        => config('api.prefix') ? config('api.prefix') . '/' . $alias : $alias,
                'as'            => 'api.' . $alias . '.',
            ], $attributes));
        });
    }
    public function map()
    {
        $this->configureRateLimiting();
        $this->mapInstallRoutes();
        $this->mapPublicApiRoutes();
        $this->mapApiRoutes();
        $this->mapCommonRoutes();
        $this->mapGuestRoutes();
        $this->mapWizardRoutes();
        $this->mapAdminRoutes();
        $this->mapPreviewRoutes();
        $this->mapPortalRoutes();
        $this->mapSignedRoutes();
        $this->mapWebRoutes();
    }
    protected function mapInstallRoutes()
    {
        Facade::prefix('install')
            ->middleware('install')
            ->namespace($this->namespace)
            ->group(base_path('routes/install.php'));
    }
    protected function mapPublicApiRoutes()
    {
        Facade::prefix(config('api.prefix'))
            ->middleware(['throttle:api'])
            ->namespace($this->namespace . '\Api')
            ->group(base_path('routes/api-public.php'));
    }
    protected function mapApiRoutes()
    {
        Facade::prefix(config('api.prefix'))
            ->domain(config('api.domain'))
            ->middleware(config('api.middleware'))
            ->namespace($this->namespace . '\Api')
            ->group(base_path('routes/api.php'));
    }
    protected function mapCommonRoutes()
    {
        Facade::prefix('{company_id}')
            ->middleware('common')
            ->namespace($this->namespace)
            ->group(base_path('routes/common.php'));
    }
    protected function mapGuestRoutes()
    {
        Facade::middleware('guest')
            ->namespace($this->namespace)
            ->group(base_path('routes/guest.php'));
    }
    protected function mapWizardRoutes()
    {
        Facade::prefix('{company_id}/wizard')
            ->middleware('wizard')
            ->namespace($this->namespace)
            ->group(base_path('routes/wizard.php'));
    }
    protected function mapAdminRoutes()
    {
        Facade::prefix('{company_id}')
            ->middleware('admin')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }
    protected function mapPreviewRoutes()
    {
        Facade::prefix('{company_id}/preview')
            ->middleware('preview')
            ->namespace($this->namespace)
            ->group(base_path('routes/preview.php'));
    }
    protected function mapPortalRoutes()
    {
        Facade::prefix('{company_id}/portal')
            ->middleware('portal')
            ->namespace($this->namespace)
            ->group(base_path('routes/portal.php'));
    }
    protected function mapSignedRoutes()
    {
        Facade::prefix('{company_id}/signed')
            ->middleware('signed')
            ->namespace($this->namespace)
            ->group(base_path('routes/signed.php'));
    }
    protected function mapWebRoutes()
    {
        Facade::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(config('app.throttles.api'));
        });
        RateLimiter::for('import', function (Request $request) {
            return Limit::perMinute(config('app.throttles.import'));
        });
        RateLimiter::for('email', function (Request $request) {
            return [
                Limit::perDay(config('app.throttles.email.month'), 30),
                Limit::perMinute(config('app.throttles.email.minute')),
            ];
        });
    }
}

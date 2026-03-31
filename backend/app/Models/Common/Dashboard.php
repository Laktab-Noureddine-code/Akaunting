<?php
namespace App\Models\Common;
use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Dashboard extends Model
{
    use Cloneable, HasFactory;
    protected $table = 'dashboards';
    protected $fillable = ['company_id', 'name', 'enabled', 'created_from', 'created_by'];
    public $sortable = ['name', 'enabled'];
    public function users()
    {
        return $this->belongsToMany(user_model_class(), 'App\Models\Auth\UserDashboard');
    }
    public function widgets()
    {
        return $this->hasMany('App\Models\Common\Widget')->orderBy('sort', 'asc');
    }
    public function scopeUserId($query, $user_id)
    {
        return $query->whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        });
    }
    public function scopeAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';
        return $query->whereHas('widgets', function ($query) use ($class) {
                    $query->where('class', 'like', $class . '%');
                })->whereDoesntHave('widgets', function ($query) use ($class) {
                    $query->where('class', 'not like', $class . '%');
                });
    }
    public function getAliasAttribute()
    {
        $alias = '';
        foreach ($this->widgets as $widget) {
            if (Str::startsWith($widget->class, 'App\\')) {
                $tmp_alias = 'core';
            } else {
                $arr = explode('\\', $widget->class);
                $tmp_alias = Str::kebab($arr[1]);
            }
            if ($alias == '') {
                $alias = $tmp_alias;
            }
            if ($alias != $tmp_alias) {
                $alias = '';
                break;
            }
        }
        return $alias;
    }
    public function getLineActionsAttribute()
    {
        $actions = [];
        if ($this->enabled) {
            $actions[] = [
                'title' => trans('general.switch'),
                'icon' => 'settings_ethernet',
                'url' => route('dashboards.switch', $this->id),
                'permission' => 'read-common-dashboards',
                'attributes' => [
                    'id' => 'index-line-actions-switch-dashboard-' . $this->id,
                ],
            ];
        }
        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('dashboards.edit', $this->id),
            'permission' => 'update-common-dashboards',
            'attributes' => [
                'id' => 'index-line-actions-edit-dashboard-' . $this->id,
            ],
        ];
        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'dashboards.destroy',
            'permission' => 'delete-common-dashboards',
            'attributes' => [
                'id' => 'index-line-actions-delete-dashboard-' . $this->id,
            ],
            'model' => $this,
        ];
        return $actions;
    }
    protected static function newFactory()
    {
        return \Database\Factories\Dashboard::new();
    }
}

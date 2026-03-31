<?php
namespace App\Models\Auth;
use Akaunting\Sortable\Traits\Sortable;
use App\Traits\Tenants;
use Laratrust\Models\LaratrustPermission;
use Laratrust\Traits\LaratrustPermissionTrait;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;
class Permission extends LaratrustPermission
{
    use LaratrustPermissionTrait, SearchString, Sortable, Tenants;
    protected $table = 'permissions';
    protected $appends = ['title'];
    protected $fillable = ['name', 'display_name', 'description'];
    public function scopeCollect($query, $sort = 'display_name')
    {
        $request = request();
        $search = $request->get('search');
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));
        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
    }
    public function scopeAction($query, $action = 'read')
    {
        return $query->where('name', 'like', $action . '-%');
    }
    public function getTitleAttribute()
    {
        $replaces = [
            'Create ' => '',
            'Read ' => '',
            'Update ' => '',
            'Delete ' => '',
            'Modules' => 'Apps',
        ];
        $title = str_replace(array_keys($replaces), array_values($replaces), $this->display_name);
        return $title;
    }
}

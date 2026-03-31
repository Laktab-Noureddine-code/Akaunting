<?php
namespace App\Models\Auth;
use Akaunting\Sortable\Traits\Sortable;
use App\Traits\Tenants;
use Bkwld\Cloner\Cloneable;
use Laratrust\Models\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;
class Role extends LaratrustRole
{
    use Cloneable, LaratrustRoleTrait, SearchString, Sortable, Tenants;
    protected $table = 'roles';
    protected $fillable = ['name', 'display_name', 'description', 'created_from', 'created_by'];
    public $cloneable_relations = ['permissions'];
    public function scopeCollect($query, $sort = 'display_name')
    {
        $request = request();
        $search = $request->get('search');
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));
        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
    }
    public function onCloning($src, $child = null)
    {
        $this->name = $src->name . '-' . Role::max('id') + 1;
    }
    public function getLineActionsAttribute()
    {
        $actions = [];
        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('roles.roles.edit', $this->id),
            'permission' => 'update-roles-roles',
            'attributes' => [
                'id' => 'index-line-actions-edit-role-' . $this->id,
            ],
        ];
        $actions[] = [
            'title' => trans('general.duplicate'),
            'icon' => 'file_copy',
            'url' => route('roles.roles.duplicate', $this->id),
            'permission' => 'create-roles-roles',
            'attributes' => [
                'id' => 'index-line-actions-duplicate-role-' . $this->id,
            ],
        ];
        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'roles.roles.destroy',
            'permission' => 'delete-roles-roles',
            'attributes' => [
                'id' => 'index-line-actions-delete-role-' . $this->id,
            ],
            'model' => $this,
        ];
        return $actions;
    }
}

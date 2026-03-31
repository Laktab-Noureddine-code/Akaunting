<?php
namespace App\Models\Module;
use App\Abstracts\Model;
class Module extends Model
{
    protected $table = 'modules';
    protected $fillable = ['company_id', 'alias', 'enabled', 'created_from', 'created_by'];
    public function scopeAlias($query, $alias)
    {
        return $query->where('alias', $alias);
    }
}

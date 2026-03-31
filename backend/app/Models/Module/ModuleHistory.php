<?php
namespace App\Models\Module;
use App\Abstracts\Model;
class ModuleHistory extends Model
{
    protected $table = 'module_histories';
    protected $fillable = ['company_id', 'module_id', 'version', 'description', 'created_from', 'created_by'];
    public function module()
    {
        return $this->belongsTo('App\Models\Module\Module');
    }
}

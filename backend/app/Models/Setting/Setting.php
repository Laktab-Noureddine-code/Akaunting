<?php
namespace App\Models\Setting;
use App\Abstracts\Model;
class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['company_id', 'key', 'value'];
    public $timestamps = false;
    public function scopePrefix($query, $prefix = 'company')
    {
        return $query->where('key', 'like', $prefix . '.%');
    }
}

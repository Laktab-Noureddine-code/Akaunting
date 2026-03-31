<?php
namespace App\Models\Setting;
use App\Abstracts\Model;
use Illuminate\Support\Str;
class EmailTemplate extends Model
{
    protected $table = 'email_templates';
    protected $appends = ['title'];
    protected $fillable = ['company_id', 'alias', 'class', 'name', 'subject', 'body', 'params', 'created_from', 'created_by'];
    public function getTitleAttribute()
    {
        return trans($this->name);
    }
    public function getGroupAttribute()
    {
        if (Str::startsWith($this->alias, 'invoice_')) {
            $group = 'general.invoices';
        } elseif (Str::startsWith($this->alias, 'bill_')) {
            $group = 'general.bills';
        } elseif (Str::startsWith($this->alias, 'payment_')) {
            $group = 'general.payments';
        } else {
            $group = 'general.others';
        }
        return $group;
    }
    public function scopeAlias($query, $alias)
    {
        return $query->where('alias', $alias);
    }
    public function scopeModuleAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';
        return $query->where('class', 'like', $class . '%');
    }
}

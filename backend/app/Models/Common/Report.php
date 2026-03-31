<?php
namespace App\Models\Common;
use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
class Report extends Model
{
    use Cloneable;
    protected $table = 'reports';
    protected $fillable = ['company_id', 'class', 'name', 'description', 'settings', 'created_from', 'created_by'];
    protected $casts = [
        'settings'      => 'object',
        'deleted_at'    => 'datetime',
    ];
    public function scopeAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';
        return $query->where('class', 'like', $class . '%');
    }
    public function scopeClass($query, $class)
    {
        return $query->where('class', '=', $class);
    }
    public function scopeExpenseSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\ExpenseSummary');
    }
    public function scopeIncomeSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\IncomeSummary');
    }
    public function scopeIncomeExpenseSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\IncomeExpenseSummary');
    }
    public function scopeProfitLoss(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\ProfitLoss');
    }
    public function scopeTaxSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\TaxSummary');
    }
    public function getAliasAttribute()
    {
        if (Str::startsWith($this->class, 'App\\')) {
            return 'core';
        }
        $arr = explode('\\', $this->class);
        return Str::kebab($arr[1]);
    }
}

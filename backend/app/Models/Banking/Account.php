<?php
namespace App\Models\Banking;
use App\Abstracts\Model;
use App\Traits\Transactions;
use App\Utilities\Str;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Account extends Model
{
    use Cloneable, HasFactory, Transactions;
    protected $table = 'accounts';
    protected $appends = ['balance', 'title', 'initials'];
    protected $fillable = ['company_id', 'type', 'name', 'number', 'currency_code', 'opening_balance', 'bank_name', 'bank_phone', 'bank_address', 'enabled', 'created_from', 'created_by'];
    protected $casts = [
        'opening_balance'   => 'double',
        'enabled'           => 'boolean',
        'deleted_at'        => 'datetime',
    ];
    public $sortable = ['name', 'number', 'balance', 'bank_name', 'bank_phone'];
    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }
    public function expense_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getExpenseTypes());
    }
    public function income_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getIncomeTypes());
    }
    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }
    public function reconciliations()
    {
        return $this->hasMany('App\Models\Banking\Reconciliation');
    }
    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }
    public function scopeNumber($query, $number)
    {
        return $query->where('number', '=', $number);
    }
    public function balanceSortable($query, $direction)
    {
        return $query
            ->orderBy('balance', $direction)
            ->select(['accounts.*', 'accounts.opening_balance as balance']);
    }
    public function getTitleAttribute()
    {
        if (! empty($this->currency) && ! empty($this->currency->symbol)) {
            return $this->name . ' (' . $this->currency->symbol . ')';
        }
        return $this->name;
    }
    public function getInitialsAttribute($value)
    {
        return Str::getInitials($this->name);
    }
    public function getBalanceAttribute()
    {
        $total = $this->opening_balance;
        $total += $this->income_transactions->sum('amount');
        $total -= $this->expense_transactions->sum('amount');
        return $total;
    }
    public function getIncomeBalanceAttribute()
    {
        $total = 0;
        $total += $this->income_transactions->sum('amount');
        return $total;
    }
    public function getExpenseBalanceAttribute()
    {
        $total = 0;
        $total += $this->expense_transactions->sum('amount');
        return $total;
    }
    public function getLineActionsAttribute()
    {
        $actions = [];
        $actions[] = [
            'title' => trans('general.show'),
            'icon' => 'visibility',
            'url' => route('accounts.show', $this->id),
            'permission' => 'read-banking-accounts',
            'attributes' => [
                'id' => 'index-line-actions-show-account-' . $this->id,
            ],
        ];
        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('accounts.edit', $this->id),
            'permission' => 'update-banking-accounts',
            'attributes' => [
                'id' => 'index-line-actions-edit-account-' . $this->id,
            ],
        ];
        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'accounts.destroy',
            'permission' => 'delete-banking-accounts',
            'model' => $this,
            'attributes' => [
                'id' => 'index-line-actions-delete-account-' . $this->id,
            ],
        ];
        return $actions;
    }
    protected static function newFactory()
    {
        return \Database\Factories\Account::new();
    }
}

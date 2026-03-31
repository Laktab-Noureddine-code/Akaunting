<?php
namespace App\Models\Setting;
use App\Abstracts\Model;
use App\Models\Document\Document;
use App\Traits\Contacts;
use App\Traits\Transactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Currency extends Model
{
    use Contacts, HasFactory, Transactions;
    protected $table = 'currencies';
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'rate',
        'enabled',
        'precision',
        'symbol',
        'symbol_first',
        'decimal_mark',
        'thousands_separator',
        'created_from',
        'created_by',
    ];
    protected $casts = [
        'rate'          => 'double',
        'enabled'       => 'boolean',
        'deleted_at'    => 'datetime',
    ];
    public $sortable = ['name', 'code', 'rate', 'enabled'];
    public function accounts()
    {
        return $this->hasMany('App\Models\Banking\Account', 'currency_code', 'code');
    }
    public function documents()
    {
        return $this->hasMany('App\Models\Document\Document', 'currency_code', 'code');
    }
    public function bills()
    {
        return $this->documents()->where('documents.type', Document::BILL_TYPE);
    }
    public function contacts()
    {
        return $this->hasMany('App\Models\Common\Contact', 'currency_code', 'code');
    }
    public function customers()
    {
        return $this->contacts()->whereIn('contacts.type', (array) $this->getCustomerTypes());
    }
    public function expense_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getExpenseTypes());
    }
    public function income_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getIncomeTypes());
    }
    public function invoices()
    {
        return $this->documents()->where('documents.type', Document::INVOICE_TYPE);
    }
    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'currency_code', 'code');
    }
    public function vendors()
    {
        return $this->contacts()->whereIn('contacts.type', (array) $this->getVendorTypes());
    }
    public function default()
    {
        return $this->code(default_currency())->first();
    }
    public function scopeCode($query, $code)
    {
        return $query->where($this->qualifyColumn('code'), $code);
    }
    public function getPrecisionAttribute($value)
    {
        if (is_null($value)) {
            return currency($this->code)->getPrecision();
        }
        return (int) $value;
    }
    public function getSymbolAttribute($value)
    {
        if (is_null($value)) {
            return currency($this->code)->getSymbol();
        }
        return $value;
    }
    public function getSymbolFirstAttribute($value)
    {
        if (is_null($value)) {
            return currency($this->code)->isSymbolFirst();
        }
        return $value;
    }
    public function getDecimalMarkAttribute($value)
    {
        if (is_null($value)) {
            return currency($this->code)->getDecimalMark();
        }
        return $value;
    }
    public function getThousandsSeparatorAttribute($value)
    {
        if (is_null($value)) {
            return currency($this->code)->getThousandsSeparator();
        }
        return $value;
    }
    public function getLineActionsAttribute()
    {
        $actions = [];
        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('currencies.edit', $this->id),
            'permission' => 'update-settings-currencies',
            'attributes' => [
                'id' => 'index-line-actions-edit-currency-' . $this->id,
            ],
        ];
        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'currencies.destroy',
            'permission' => 'delete-settings-currencies',
            'attributes' => [
                'id' => 'index-line-actions-delete-currency-' . $this->id,
            ],
            'model' => $this,
        ];
        return $actions;
    }
    protected static function newFactory()
    {
        return \Database\Factories\Currency::new();
    }
}

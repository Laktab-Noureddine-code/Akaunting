<?php
namespace App\Models\Common;
use Akaunting\Sortable\Traits\Sortable;
use App\Events\Common\CompanyForgettingCurrent;
use App\Events\Common\CompanyForgotCurrent;
use App\Events\Common\CompanyMadeCurrent;
use App\Events\Common\CompanyMakingCurrent;
use App\Models\Document\Document;
use App\Traits\Contacts;
use App\Traits\Media;
use App\Traits\Owners;
use App\Traits\Sources;
use App\Traits\Tenants;
use App\Traits\Transactions;
use App\Utilities\Overrider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Contracts\Ownable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;
class Company extends Eloquent implements Ownable
{
    use Contacts, HasFactory, Media, Owners, SearchString, SoftDeletes, Sortable, Sources, Tenants, Transactions;
    protected $table = 'companies';
    protected $appends = ['location'];
    protected $fillable = ['domain', 'enabled', 'created_from', 'created_by'];
    protected $casts = [
        'enabled'       => 'boolean',
        'deleted_at'    => 'datetime',
    ];
    public $allAttributes = [];
    public $sortable = ['id', 'name', 'domain', 'email', 'phone', 'enabled', 'created_at', 'tax_number', 'country', 'currency'];
    public function fill(array $attributes)
    {
        $this->allAttributes = $attributes;
        return parent::fill($attributes);
    }
    public static function boot()
    {
        parent::boot();
        try {
            static::retrieved(function($model) {
                $model->setCommonSettingsAsAttributes();
            });
            static::saving(function($model) {
                $model->unsetCommonSettingsFromAttributes();
            });
        } catch(\Throwable $e) {
        }
    }
    public function documents()
    {
        return $this->hasMany('App\Models\Document\Document');
    }
    public function document_histories()
    {
        return $this->hasMany('App\Models\Document\DocumentHistory');
    }
    public function document_items()
    {
        return $this->hasMany('App\Models\Document\DocumentItem');
    }
    public function document_item_taxes()
    {
        return $this->hasMany('App\Models\Document\DocumentItemTax');
    }
    public function document_totals()
    {
        return $this->hasMany('App\Models\Document\DocumentTotal');
    }
    public function accounts()
    {
        return $this->hasMany('App\Models\Banking\Account');
    }
    public function bills()
    {
        return $this->documents()->where('type', Document::BILL_TYPE);
    }
    public function bill_histories()
    {
        return $this->document_histories()->where('type', Document::BILL_TYPE);
    }
    public function bill_items()
    {
        return $this->document_items()->where('type', Document::BILL_TYPE);
    }
    public function bill_item_taxes()
    {
        return $this->document_item_taxes()->where('type', Document::BILL_TYPE);
    }
    public function bill_totals()
    {
        return $this->document_totals()->where('type', Document::BILL_TYPE);
    }
    public function categories()
    {
        return $this->hasMany('App\Models\Setting\Category');
    }
    public function contacts()
    {
        return $this->hasMany('App\Models\Common\Contact');
    }
    public function contact_persons()
    {
        return $this->hasMany('App\Models\Common\ContactPerson');
    }
    public function currencies()
    {
        return $this->hasMany('App\Models\Setting\Currency');
    }
    public function customers()
    {
        return $this->contacts()->whereIn('type', (array) $this->getCustomerTypes());
    }
    public function dashboards()
    {
        return $this->hasMany('App\Models\Common\Dashboard');
    }
    public function email_templates()
    {
        return $this->hasMany('App\Models\Setting\EmailTemplate');
    }
    public function expense_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getExpenseTypes());
    }
    public function income_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getIncomeTypes());
    }
    public function invoices()
    {
        return $this->documents()->where('type', Document::INVOICE_TYPE);
    }
    public function invoice_histories()
    {
        return $this->document_histories()->where('type', Document::INVOICE_TYPE);
    }
    public function invoice_items()
    {
        return $this->document_items()->where('type', Document::INVOICE_TYPE);
    }
    public function invoice_item_taxes()
    {
        return $this->document_item_taxes()->where('type', Document::INVOICE_TYPE);
    }
    public function invoice_totals()
    {
        return $this->document_totals()->where('type', Document::INVOICE_TYPE);
    }
    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
    }
    public function item_taxes()
    {
        return $this->hasMany('App\Models\Common\ItemTax');
    }
    public function modules()
    {
        return $this->hasMany('App\Models\Module\Module');
    }
    public function module_histories()
    {
        return $this->hasMany('App\Models\Module\ModuleHistory');
    }
    public function owner()
    {
        return $this->belongsTo(user_model_class(), 'created_by', 'id')->withDefault(['name' => trans('general.na')]);
    }
    public function reconciliations()
    {
        return $this->hasMany('App\Models\Banking\Reconciliation');
    }
    public function recurring()
    {
        return $this->hasMany('App\Models\Common\Recurring');
    }
    public function reports()
    {
        return $this->hasMany('App\Models\Common\Report');
    }
    public function settings()
    {
        return $this->hasMany('App\Models\Setting\Setting');
    }
    public function taxes()
    {
        return $this->hasMany('App\Models\Setting\Tax');
    }
    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }
    public function transaction_taxes()
    {
        return $this->hasMany('App\Models\Banking\TransactionTax');
    }
    public function transfers()
    {
        return $this->hasMany('App\Models\Banking\Transfer');
    }
    public function users()
    {
        return $this->belongsToMany(user_model_class(), 'App\Models\Auth\UserCompany');
    }
    public function vendors()
    {
        return $this->contacts()->whereIn('type', (array) $this->getVendorTypes());
    }
    public function widgets()
    {
        return $this->hasMany('App\Models\Common\Widget');
    }
    public function setCommonSettingsAsAttributes()
    {
        try { 
            $settings = $this->settings;
            $groups = [
                'company',
                'default',
            ];
            foreach ($settings as $setting) {
                list($group, $key) = explode('.', $setting->getAttribute('key'));
                if (! in_array($group, $groups)) {
                    continue;
                }
                $value = $setting->getAttribute('value');
                if (($key == 'logo') && empty($value)) {
                    $value = 'public/img/company.png';
                }
                $this->setAttribute($key, $value);
            }
            if ($this->getAttribute('logo') == '') {
                $this->setAttribute('logo', 'public/img/company.png');
            }
            if ($this->getAttribute('currency') == '') {
                $this->setAttribute('currency', config('setting.fallback.default.currency'));
            }
        } catch(\Throwable $e) {
        }
    }
    public function unsetCommonSettingsFromAttributes()
    {
        try { 
            $settings = $this->settings;
            $groups = [
                'company',
                'default',
            ];
            foreach ($settings as $setting) {
                list($group, $key) = explode('.', $setting->getAttribute('key'));
                if (! in_array($group, $groups)) {
                    continue;
                }
                $this->offsetUnset($key);
            }
            $this->offsetUnset('logo');
            $this->offsetUnset('currency');
        } catch(\Throwable $e) {
        }
    }
    public function scopeCollect($query, $sort = 'name')
    {
        $request = request();
        $search = $request->get('search');
        $query->usingSearchString($search)->sortable($sort);
        if ($request->expectsJson() && $request->isNotApi()) {
            return $query->get();
        }
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));
        return $query->paginate($limit);
    }
    public function scopeEnabled($query, $value = 1)
    {
        return $query->where('enabled', $value);
    }
    public function scopeUserId($query, $user_id)
    {
        return $query->whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        });
    }
    public function nameSortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'company.name')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }
    public function emailSortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'company.email')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }
    public function phoneSortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'company.phone')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }
    public function taxNumberSortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'company.tax_number')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }
    public function countrySortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'company.country')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }
    public function currencySortable($query, $direction)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where('key', 'default.currency')
            ->orderBy('value', $direction)
            ->select('companies.*');
    }
    public function scopeAutocomplete($query, $filter)
    {
        return $query->join('settings', 'companies.id', '=', 'settings.company_id')
            ->where(function ($query) use ($filter) {
                foreach ($filter as $key => $value) {
                    $column = $key;
                    if (!in_array($key, $this->fillable)) {
                        $column = 'company.' . $key;
                        $query->orWhere('key', $column);
                        $query->Where('value', 'LIKE', "%" . $value  . "%");
                    } else {
                        $query->orWhere($column, 'LIKE', "%" . $value  . "%");
                    }
                }
            })
            ->select('companies.*');
    }
    public function getCompanyLogoAttribute()
    {
        return $this->getMedia('company.logo')->last();
    }
    public function getLocationAttribute()
    {
        $country = setting('company.country');
        if ($country && array_key_exists($country, trans('countries'))) {
            $trans_country = trans('countries.' . $country);
        }
        return $this->getFormattedAddress(setting('company.city'), $trans_country ?? null, setting('company.state'), setting('company.zip_code'));
    }
    public function getLineActionsAttribute()
    {
        $actions = [];
        if ($this->enabled) {
            $actions[] = [
                'title' => trans('general.switch'),
                'icon' => 'settings_ethernet',
                'url' => route('companies.switch', $this->id),
                'attributes' => [
                    'id' => 'index-line-actions-switch-company-' . $this->id,
                ],
            ];
        }
        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('companies.edit', $this->id),
            'permission' => 'update-common-companies',
            'attributes' => [
                'id' => 'index-line-actions-edit-company-' . $this->id,
            ],
        ];
        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'companies.destroy',
            'permission' => 'delete-common-companies',
            'attributes' => [
                'id' => 'index-line-actions-delete-company-' . $this->id,
            ],
            'model' => $this,
        ];
        return $actions;
    }
    public function makeCurrent($force = false)
    {
        if (!$force && $this->isCurrent()) {
            return $this;
        }
        static::forgetCurrent();
        event(new CompanyMakingCurrent($this));
        app()->instance(static::class, $this);
        setting()->setExtraColumns(['company_id' => $this->id]);
        setting()->forgetAll();
        setting()->load(true);
        Overrider::load('settings');
        Overrider::load('currencies');
        event(new CompanyMadeCurrent($this));
        return $this;
    }
    public function isCurrent()
    {
        return static::getCurrent()?->id === $this->id;
    }
    public function isNotCurrent()
    {
        return !$this->isCurrent();
    }
    public static function getCurrent()
    {
        if (!app()->has(static::class)) {
            return null;
        }
        return app(static::class);
    }
    public static function forgetCurrent()
    {
        $current = static::getCurrent();
        if (is_null($current)) {
            return null;
        }
        event(new CompanyForgettingCurrent($current));
        app()->forgetInstance(static::class);
        setting()->forgetAll();
        event(new CompanyForgotCurrent($current));
        return $current;
    }
    public static function hasCurrent()
    {
        return static::getCurrent() !== null;
    }
    public function scopeSource($query, $source)
    {
        return $query->where($this->qualifyColumn('created_from'), $source);
    }
    public function scopeIsOwner($query)
    {
        return $query->where($this->qualifyColumn('created_by'), user_id());
    }
    public function scopeIsNotOwner($query)
    {
        return $query->where($this->qualifyColumn('created_by'), '<>', user_id());
    }
    public function ownerKey($owner)
    {
        if ($this->isNotOwnable()) {
            return 0;
        }
        return $this->created_by;
    }
    protected static function newFactory()
    {
        return \Database\Factories\Company::new();
    }
}

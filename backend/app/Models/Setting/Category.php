<?php
namespace App\Models\Setting;
use App\Abstracts\Model;
use App\Builders\Category as Builder;
use App\Models\Document\Document;
use App\Interfaces\Export\WithParentSheet;
use App\Relations\HasMany\Category as HasMany;
use App\Scopes\Category as Scope;
use App\Traits\Categories;
use App\Traits\Tailwind;
use App\Traits\Transactions;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
class Category extends Model
{
    use Categories, HasFactory, Tailwind, Transactions;
    public const INCOME_TYPE = 'income';
    public const EXPENSE_TYPE = 'expense';
    public const ITEM_TYPE = 'item';
    public const OTHER_TYPE = 'other';
    protected $table = 'categories';
    protected $appends = ['display_name', 'color_hex_code'];
    protected $fillable = ['company_id', 'name', 'type', 'color', 'enabled', 'created_from', 'created_by', 'parent_id'];
    public $sortable = ['name', 'type', 'enabled'];
    protected static function booted()
    {
        static::addGlobalScope(new Scope);
    }
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }
    protected function newHasMany(EloquentBuilder $query, EloquentModel $parent, $foreignKey, $localKey)
    {
        return new HasMany($query, $parent, $foreignKey, $localKey);
    }
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBindingQuery($this, $value, $field)
            ->withoutGlobalScope(Scope::class)
            ->getWithoutChildren()
            ->first();
    }
    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->withSubCategory();
    }
    public function sub_categories()
    {
        return $this->hasMany(Category::class, 'parent_id')->withSubCategory()->with('categories')->orderBy('name');
    }
    public function documents()
    {
        return $this->hasMany('App\Models\Document\Document');
    }
    public function bills()
    {
        return $this->documents()->where('documents.type', Document::BILL_TYPE);
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
    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
    }
    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }
    public function scopeType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }
        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }
    public function scopeIncome($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'income');
    }
    public function scopeExpense($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'expense');
    }
    public function scopeItem($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'item');
    }
    public function scopeOther($query)
    {
        return $query->where($this->qualifyColumn('type'), '=', 'other');
    }
    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }
    public function scopeWithSubCategory($query)
    {
        return $query->withoutGlobalScope(new Scope);
    }
    public function scopeCollectForExport($query, $ids = [], $sort = 'name', $id_field = 'id')
    {
        $request = request();
        if (!empty($ids)) {
            $query->whereIn($id_field, (array) $ids);
        }
        $search = $request->get('search');
        $query->withSubcategory();
        $query->usingSearchString($search)->sortable($sort);
        $page = (int) $request->get('page');
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));
        $offset = $page ? ($page - 1) * $limit : 0;
        if (! $this instanceof WithParentSheet && count((array) $ids) < $limit) {
            $query->offset($offset)->limit($limit);
        }
        return $query->cursor();
    }
    public function getColorHexCodeAttribute(): string
    {
        $color = $this->color ?? 'green-500';
        return $this->getHexCodeOfTailwindClass($color);
    }
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . ucfirst($this->type) . ')';
    }
    public function getLineActionsAttribute()
    {
        $actions = [];
        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'create',
            'url' => route('categories.edit', $this->id),
            'permission' => 'update-settings-categories',
            'attributes' => [
                'id' => 'index-line-actions-edit-category-' . $this->id,
            ],
        ];
        if ($this->isTransferCategory()) {
            return $actions;
        }
        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'categories.destroy',
            'permission' => 'delete-settings-categories',
            'attributes' => [
                'id' => 'index-line-actions-delete-category-' . $this->id,
            ],
            'model' => $this,
        ];
        return $actions;
    }
    protected static function newFactory()
    {
        return \Database\Factories\Category::new();
    }
}

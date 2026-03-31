<?php
namespace App\Models\Banking;
use App\Abstracts\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Reconciliation extends Model
{
    use HasFactory;
    protected $table = 'reconciliations';
    protected $fillable = ['company_id', 'account_id', 'started_at', 'ended_at', 'closing_balance', 'transactions', 'reconciled', 'created_from', 'created_by'];
    protected $casts = [
        'closing_balance'   => 'double',
        'reconciled'        => 'boolean',
        'transactions'      => 'array',
        'deleted_at'        => 'datetime',
        'started_at'        => 'datetime',
        'ended_at'          => 'datetime',
    ];
    public $sortable = ['created_at', 'account_id', 'started_at', 'ended_at', 'opening_balance', 'closing_balance', 'reconciled'];
    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account')->withDefault(['name' => trans('general.na')]);
    }
    public function getLineActionsAttribute()
    {
        $actions = [];
        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('reconciliations.edit', $this->id),
            'permission' => 'update-banking-reconciliations',
            'attributes' => [
                'id' => 'index-line-actions-edit-reconciliation-' . $this->id,
            ],
        ];
        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'reconciliations.destroy',
            'permission' => 'delete-banking-reconciliations',
            'attributes' => [
                'id' => 'index-line-actions-delete-reconciliation-' . $this->id,
            ],
            'model' => $this,
        ];
        return $actions;
    }
    protected static function newFactory()
    {
        return \Database\Factories\Reconciliation::new();
    }
}

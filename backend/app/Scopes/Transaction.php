<?php
namespace App\Scopes;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
class Transaction implements Scope
{
    use Scopes;
    public function apply(Builder $builder, Model $model)
    {
        $this->applyNotRecurringScope($builder, $model);
        $this->applyNotSplitScope($builder, $model);
    }
}

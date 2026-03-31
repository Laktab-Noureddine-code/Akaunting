<?php
namespace App\Scopes;
use App\Traits\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
class Contact implements Scope
{
    use Scopes;
    public function apply(Builder $builder, Model $model)
    {
    }
}

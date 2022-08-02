<?php


namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LatestScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Menggunakan constants untuk berfungsi pada semua model
        $builder->orderBy($model::CREATED_AT, 'desc');
    }
}

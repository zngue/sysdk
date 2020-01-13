<?php

namespace ShangYou\Supports;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\Request;
use ShangYou\Helpers\UserAware;

class UserScope implements Scope
{

    use UserAware;

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model   $model
     *
     * @return void
     */
    public function apply(Builder $builder, EloquentModel $model)
    {

        if ($model instanceof UserScopeInterface) {
            /** @var Request $request */
            $request = app('request');
            $model->localScope($builder, $request);
        }
    }
}
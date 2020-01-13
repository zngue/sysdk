<?php
namespace ShangYou\Supports;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface UserScopeInterface
{
    /**
     * 限制查询
     *
     * @param Builder $builder
     * @param Request $request
     *
     * @return mixed
     */
    public function localScope(Builder $builder, Request $request);
}
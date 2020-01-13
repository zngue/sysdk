<?php
namespace ShangYou\Supports;


abstract class Model extends \Illuminate\Database\Eloquent\Model
{

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new UserScope());
    }
}
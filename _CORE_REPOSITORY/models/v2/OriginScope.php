<?php
// Scope
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class OriginScope implements ScopeInterface
{

    public function apply(Builder $builder)
    {
        $builder->where('origin', '=', MY_NODE);
    }

    public function remove(Builder $builder)
    { // you don't need this }
    }
}
<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Counter extends Eloquent
{
    protected $connection = 'coreCaDb';
    public $timestamps = false;
    protected $table = 'vks_counters';

    protected $fillable = [
        'origin',
        'ip',
        'request',
        'type',
        'created_at',
        'updated_at'
    ];

}
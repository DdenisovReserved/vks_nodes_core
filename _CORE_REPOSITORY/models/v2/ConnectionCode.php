<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class ConnectionCode extends Eloquent
{
    protected $table = 'vks_store_link_codes';

    public $timestamps = false;

    protected $fillable = [
        'vks_id', 'conn_code_id', 'value','tip'
    ];

    public function vks()
    {
        return $this->belongsTo('Vks');
    }
}
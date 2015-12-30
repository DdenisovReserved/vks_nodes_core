<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class CAConnectionCode extends Eloquent
{
    protected $connection = 'coreCaDb';

    protected $table = 'vks_store_link_codes';

    public $timestamps = false;

    protected $fillable = [
        'vks_id', 'conn_code_id', 'value'
    ];

    public function vks()
    {
        return $this->belongsTo('CAVks');
    }
}
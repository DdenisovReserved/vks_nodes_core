<?php
use \Illuminate\Database\Eloquent\Model;

class CACustomLocation extends Model
{

    protected $connection = 'coreCaDb';

    protected $table = 'vks_store_location';

    protected $fillable = [
        'vks_id','location'
    ];

    public function vks() {
        return $this->belongsTo('CAVks');
    }
}
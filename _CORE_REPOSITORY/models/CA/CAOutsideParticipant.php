<?php

class CAOutsideParticipant extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'coreCaDb';


    protected $table = 'vks_store_participants_outside';

    protected $fillable = [
        'vks_id','attendance_value'
    ];
    public function vks() {
        return $this->belongsTo('CAVks');
    }
}
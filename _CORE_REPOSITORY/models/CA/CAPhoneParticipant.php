<?php

class CAPhoneParticipant extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'coreCaDb';

    protected $table = 'vks_store_participants_phones';

    protected $fillable = [
        'vks_id','phone_num'
    ];
    public function vks() {
        return $this->belongsTo('CAVks');
    }
}
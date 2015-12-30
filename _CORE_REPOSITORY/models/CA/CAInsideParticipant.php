<?php

class CAInsideParticipant extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'coreCaDb';

    protected $table = 'vks_store_participants_inside';

    protected $fillable = [
        'vks_id',
        'attendance_id',
        'full_path'
    ];

    public function vks() {
        return $this->belongsTo('CAVks');
    }
}
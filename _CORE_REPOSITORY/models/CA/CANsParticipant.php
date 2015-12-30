<?php

class CANsParticipant extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'coreCaDb';

    protected $table = 'vks_ns_participants';

    protected $fillable = [
        'vks_id',
        'attendance_id',
        'full_path'
    ];

    public function vks() {
        return $this->belongsTo('CAVksNoSupport');
    }
}
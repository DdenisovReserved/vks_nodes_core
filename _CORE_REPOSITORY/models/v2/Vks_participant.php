<?php

class Vks_participant extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'vks_participants';

    protected $fillable = [
        'vks_id',
        'attendance_id',
    ];

    public function vks() {
        return $this->belongsTo('Vks');
    }
    public function att() {
        return $this->hasOne('Attendance');
    }
}
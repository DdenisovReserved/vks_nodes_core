<?php
class CAAttendanceInnerPoint extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'coreCaDb';

    protected $table = 'attendance_inner_points';

    protected $fillable = [
        'name', 'ip', 'attendance_id'
    ];

    public function parent()
    {
        return $this->hasOne('CAAttendance');
    }

}
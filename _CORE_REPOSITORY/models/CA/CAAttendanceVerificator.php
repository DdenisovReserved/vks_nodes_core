<?php
class CAAttendanceVerificator extends \Illuminate\Database\Eloquent\Model
{
    protected $connection = 'coreCaDb';

    protected $table = 'attendance_verificators';

    protected $fillable = [
        'user_id', 'attendance_id','user_name'
    ];

    public $timestamps = false;

    public function parent()
    {
        return $this->hasOne('CAAttendance');
    }


}
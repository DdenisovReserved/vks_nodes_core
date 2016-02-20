<?php
use \Illuminate\Database\Eloquent\Model;
class CAAttendance extends Model
{
    protected $connection = 'coreCaDb';

    protected $table = 'attendance';

    protected $fillable = [
        'name','parent_id','container','verifiable','tech_supp_mail','is_tb'
    ];


    public function scopeTbs($query) {
        return $query->where('is_tb', 1);
    }

    public function childs ()
    {
        return $this->hasMany('CAAttendance','parent_id','id');
    }

    public function innerPoints ()
    {
        return $this->hasMany('CAAttendanceInnerPoint');
    }

    public function verificators ()
    {
        return $this->hasMany('CAAttendanceVerificator');
    }


}
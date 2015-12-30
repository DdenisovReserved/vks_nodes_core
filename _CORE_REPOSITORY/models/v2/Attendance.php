<?php
use \Illuminate\Database\Eloquent\Model as Eloquent;
class Attendance extends Eloquent
{
    protected $table = 'attendance';

    protected $appends = array('full_path');

    protected $fillable = [
        'name','parent_id','container', 'ip', 'active', 'check'
    ];

    public function childs ()
    {
        return $this->hasMany('Attendance','parent_id','id');
    }

    public function getFullPathAttribute()
    {
        return AttendanceNew_controller::makeFullPath($this->id);
    }

    public static function boot()
    {
        parent::boot();

        parent::observe(new AttendanceObserver());
    }

}
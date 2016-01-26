<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Attendance extends Eloquent
{
    protected $table = 'attendance';

    protected $appends = array('full_path');

    protected $fillable = [
        'name', 'parent_id', 'container', 'ip', 'active', 'check','tech_supportable'
    ];


    public function parent()
    {
        return $this->hasOne('Attendance', 'id', 'parent_id');
    }

    public function childs()
    {
        return $this->hasMany('Attendance', 'parent_id', 'id');
    }

    public function tech_support_requests()
    {
        return $this->hasMany('TechSupportRequest', 'att_id', 'id');
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


    public function scopeFull($query)
    {
        return $query->with('childs', 'tech_support_container', 'tech_support_engineers');
    }

    public function scopeTechSupportable($query)
    {
        return $query->where('tech_supportable', 1);
    }
}
<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class CARelationLink extends Eloquent
{
    protected $connection = 'coreCaDb';

    protected $table = 'vks_relations_links';

    protected $fillable = [
        'vks_id','attendance_id',
        'responce','comment',
        'conn_point','accepted_by',
        'created_at', 'updated_at',
    ];

    public function vks() {
        return $this->hasOne("CAVks",'id','vks_id');
    }
    public function attendance() {
        return $this->hasOne("CAAttendance",'id','attendance_id');
    }
}
<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class CAVksNoSupport extends Eloquent
{
    protected $connection = 'coreCaDb';

    protected $table = 'auto_generated_vks_store';

    protected $fillable = [
        'title','date','start_date_time','end_date_time',
        'owner_id',
        'status',
        'location',
        'v_room_num',
        'from_ip', 'needTPSupport','is_private','ca_participants','referrer'
    ];
    public function scopeApproved($query) {
        return $query->where('status',VKS_STATUS_APPROVED);
    }
    public function owner() {
        return $this->hasOne('CAUser','id','owner_id');
    }
    public function participants() {
        return $this->hasMany('CANsParticipant','vks_id','id');
    }
}
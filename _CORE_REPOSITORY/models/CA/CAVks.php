<?php
use \Illuminate\Database\Eloquent\Model;

class CAVks extends Model
{
    protected $connection = 'coreCaDb';

    protected $table = 'vks_store';

    protected $fillable = [
        'title','date','start_date_time','end_date_time',
        'supp_admin_login',
        'init_customer_fio','init_head_fio','init_customer_phone',
        'status','approved_by',
        'comment_for_admin','comment_for_user',
        'presentation',
        'owner_id',
        'created',
        'submitted',
        'from_ip','from_vip', 'needTPSupport',
        'is_verified_by_user','referrer',
        'flag'
    ];

    public function location () {
        //get location from pivot table (vks_store_location)
        $location = App::$instance->capsule->connection('coreCaDb')->table('vks_store_location')->where('vks_id',$this->id)->first(['location']);

        //try find it in attendance table

//        dump();
            $tryGetAtt = (!AttendanceNew_controller::isLocationString($location['location'])) ? CAAttendance::find($location['location']) : false;

//        dump($tryGetAtt);

        if (!$tryGetAtt) {
            return $this->hasOne('CACustomLocation', 'vks_id', 'id');
        } else {
            $this->location = $location['location'];
            return $this->hasOne('CAAttendance','id','location');
        }
    }

    public function scopeApproved($query) {
        return $query->where('status',VKS_STATUS_APPROVED);
    }
    public function scopeNotApproved($query) {
        return $query->where('status',VKS_STATUS_PENDING);
    }
    public function scopeFull($query) {
        return $query->with('location','insideParp','outsideParp','phoneParp','connectionCode','relationLinks','owner','admin');
    }

    public function insideParp() {
        return $this->hasMany('CAInsideParticipant','vks_id','id');
    }
    public function outsideParp() {
        return $this->hasMany('CAOutsideParticipant','vks_id','id');
    }
    public function phoneParp() {
        return $this->hasMany('CAPhoneParticipant','vks_id','id');
    }
    public function connection_codes() {
        return $this->hasOne('CAConnectionCode','vks_id','id');
    }
    public function owner() {
        return $this->hasOne('CAUser','id','owner_id');
    }
    public function admin() {
        return $this->hasOne('CAUser','id','admin_id');
    }
    public function relationLinks() {
        return $this->hasMany('CARelationLink');
    }

}
<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Vks extends Eloquent
{
    const TYPE_REGULAR = 0;
    const TYPE_SIMPLE = 1;

    protected $table = 'vks_store';
    protected $appends = array('is_applyable', 'is_tech_supportable', 'participants_count');
    protected $fillable = [
        'title', 'date', 'start_date_time', 'end_date_time',
        'department', 'initiator',
        'other_tb_required', 'ca_code',
        'init_customer_fio', 'init_customer_mail', 'init_customer_phone',
        'status', 'approved_by',
        'comment_for_admin', 'comment_for_user',
        'presentation',
        'in_place_participants_count',
        'link_to_ca',
        'owner_id',
        'from_ip', 'link_ca_vks_type', 'link_ca_vks_id',
        'record_required', 'is_private', 'vks_stack_id'

    ];

    public function getIsApplyableAttribute()
    {
        if (date_create() < date_create($this->end_date_time)
            && in_array($this->status, array(VKS_STATUS_APPROVED, VKS_STATUS_PENDING))
            && !$this->is_simple
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function getIsTechSupportableAttribute()
    {
        if (date_create($this->end_date_time) > date_create()
            && in_array($this->status, array(VKS_STATUS_APPROVED, VKS_STATUS_PENDING))
            && !$this->is_simple
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function getParticipantsCountAttribute()
    {
        $participants_ids = array();
        if ($this->participants instanceof Illuminate\Database\Eloquent\Collection) {
            $c = count($this->participants);
            if ($c)
                for ($i = 0; $i < $c; $i++)
                    $participants_ids[] = $this->participants[$i]->id;
        }
        if ($this->tech_support_requests instanceof Illuminate\Database\Eloquent\Collection) {
            $c = count($this->tech_support_requests);
            if ($c)
                for ($i = 0; $i < $c; $i++) {
                    if ($this->tech_support_requests[$i]->status != TechSupportRequest::STATUS_USER_REFUSE)
                        $participants_ids[] = $this->tech_support_requests[$i]->att_id;
                }
        }

        $participants_ids = array_unique($participants_ids);

        return $this->in_place_participants_count + count($participants_ids);
    }


    public function scopeApproved($query)
    {
        return $query->where('status', VKS_STATUS_APPROVED);
    }

    public function scopeNotEnded($query)
    {
        return $query->where('end_date_time', '>', date_create());
    }

    public function scopeNotApproved($query)
    {
        return $query->where('status', VKS_STATUS_PENDING)->where('is_simple', 0);
    }

    public function scopeNotSimple($query)
    {
        return $query->where('is_simple', 0);
    }

    public function scopeSimple($query)
    {
        return $query->where('is_simple', 1);
    }

    public function scopeFull($query)
    {
        return $query->with(
            'participants',
            'connection_codes',
            'owner',
            'initiator_rel',
            'department_rel',
            'approver',
            'stack',
            'tech_support_requests'
        );
    }

    public function participants()
    {
        return $this->belongsToMany("Attendance", "Vks_participants", 'vks_id', 'attendance_id');
    }

    public function connection_codes()
    {
        return $this->hasMany('ConnectionCode');
    }

    public function owner()
    {
        return $this->hasOne('User', 'id', 'owner_id');
    }

    public function initiator_rel()
    {
        return $this->hasOne('Initiator', 'id', 'initiator');
    }

    public function department_rel()
    {
        return $this->hasOne('Department', 'id', 'department');
    }

    public function approver()
    {
        return $this->hasOne('User', 'id', 'approved_by');
    }

    public function tech_support_requests()
    {
        return $this->hasMany('TechSupportRequest', 'vks_id', 'id');
    }

    public function stack()
    {
        return $this->hasOne('VksStack', 'id', 'vks_stack_id');
    }

    public static function boot()
    {
        parent::boot();

        parent::observe(new VksObserver());
    }

}
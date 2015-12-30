<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class CAUser extends Eloquent
{
    protected $connection = 'coreCaDb';

    protected $table = 'users';

    protected $fillable = [
        'role',
        'email',
        'is_from_domain',
        'fio',
        'phone',
        'lc',
        'status',
        'last_visit',
        'login_count',
        'origin',
        'colors'
    ];

    protected $guarded = [
        'password'
    ];

    public function Vks() {
        return $this->hasMany('Vks','owner_id','id');
    }

    public function logs() {
        return $this->hasMany("LogRecord",'by_user','id');
    }

    public function scopeApproved($query) {
        return $query->where('status', USER_STATUS_APPROVED);

    }

}
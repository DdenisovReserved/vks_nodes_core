<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    protected $connection = 'coreCaDb';

    protected $table = 'users';

    protected $fillable = [
        'login',
        'role',
        'fio',
        'phone',
        'token',
        'status',
        'last_visit',
        'login_count',
        'origin',
        'email'
    ];

    public static function boot()
    {
        static::addGlobalScope(new OriginScope);
    }


    public function getOriginAttribute()
    {
        return MY_NODE;
    }

    public function setOriginAttribute()
    {
        $this->attributes['origin'] = MY_NODE;
    }


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
<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class OutlookCalendarRequest extends Eloquent
{

    const REQUEST_TYPE_NEW = 0;
    const REQUEST_TYPE_UPDATE = 1;
    const REQUEST_TYPE_DELETE = 2;

    const SEND_STATUS_REQUIRED = 0;
    const SEND_STATUS_COMPLETED = 1;

    protected $table = 'outlook_calendar_requests';

    protected $fillable = [
        'vks_id',
        'user_id',
        'request_type',
        'send_status',

    ];

    public function scopeNotSended($query) {
        return $query->where('send_status', OutlookCalendarRequest::SEND_STATUS_REQUIRED);
    }


    public function vks() {
        return $this->HasOne('Vks','id','vks_id');
    }

    public function user() {
        return $this->HasOne('CAUser','id','user_id');
    }




}
<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class LogRecord extends Eloquent
{
    protected $table = 'log';

    protected $fillable = [
        'event_type',
        'from_ip',
        'by_user',
        'content',
    ];
    public function user() {
        return $this->hasOne("User",'id','by_user');
    }
}
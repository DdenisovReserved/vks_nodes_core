<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Notification extends Eloquent
{
    protected $table = 'notifications';

    protected $fillable = [

        "type", "to","message",'is_read','alarm'

    ];


}


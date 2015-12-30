<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class MailStack extends Eloquent
{

    protected $connection = 'coreCaDb';

    protected $table = 'mail_stack';

    protected $fillable = [
        'address',
        'theme',
        'message',
        'status',
        'owner_ip',
    ];
}
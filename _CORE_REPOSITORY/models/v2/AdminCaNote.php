<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class AdminCaNote extends Eloquent
{
    protected $table = 'admin_ca_notes';

    protected $fillable = [
        'vks_id',
        'note',
        'owner_id'
    ];

    public function owner()
    {
        return $this->hasOne('User', 'id', 'owner_id');
    }
    public function vks()
    {
        return $this->hasOne('CAVks', 'id', 'vks_id');
    }
}
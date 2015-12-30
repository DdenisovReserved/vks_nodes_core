<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class VksVersion extends Eloquent
{
    protected $table = 'vks_versions';

    protected $fillable = [
        'vks_id',
        'version',
        'dump',
        'changed_by',
    ];

    public function changer() {
        return $this->hasOne('User','id','changed_by');
    }
}
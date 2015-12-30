<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Initiator extends Eloquent
{
    protected $table = 'initiators';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function vks() {
        return $this->hasMany('Vks','initiator','id');
    }
}
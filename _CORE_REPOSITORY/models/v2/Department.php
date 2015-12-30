<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Department extends Eloquent
{
    protected $table = 'departments';

    public $timestamps = false;

    protected $fillable = [
        'prefix',
        'name'
    ];

    public function vks() {
        return $this->hasMany('Vks','department','id');
    }
}
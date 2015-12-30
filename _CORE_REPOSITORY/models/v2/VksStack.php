<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class VksStack extends Eloquent
{
    protected $table = 'vks_stack';

    protected $fillable = [
        'created_at',
        'updated_at'
    ];

    public function vkses() {
        return $this->hasMany('Vks','vks_stack_id','id');
    }
}
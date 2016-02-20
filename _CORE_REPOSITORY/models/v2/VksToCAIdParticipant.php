<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class VksToCAIdParticipant extends Eloquent
{
    protected $table = 'vks_to_ca_id_participants';

    public $timestamps = false;

    protected $fillable = [
        'vks_id',
        'ca_att_id',
    ];

    public function vks()
    {
        return $this->belongsTo('Vks');
    }
}
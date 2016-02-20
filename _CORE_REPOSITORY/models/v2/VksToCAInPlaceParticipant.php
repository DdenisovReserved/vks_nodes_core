<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class VksToCAInPlaceParticipant extends Eloquent
{
    protected $table = 'vks_to_ca_inplace_participants';

    public $timestamps = false;

    protected $fillable = [
        'vks_id',
        'participants_count',
    ];

    public function vks()
    {
        return $this->belongsTo('Vks');
    }


}
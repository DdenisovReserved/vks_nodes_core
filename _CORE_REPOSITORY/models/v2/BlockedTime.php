<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class BlockedTime extends Eloquent
{
    protected $table = 'blocked_time';

    protected $appends = array('blocked_type_named');

    protected $fillable = [
        'start_at',
        'end_at',
        'vks_type_blocked',
        'description'
    ];

    public function getStartAtAttribute($value)
    {
        if ($value instanceof DateTime)
            return $value->format("d.m.Y H:i");
        else
            return date_create($value)->format("d.m.Y H:i");
    }

    public function getEndAtAttribute($value)
    {
        if ($value instanceof DateTime)
            return $value->format("d.m.Y H:i");
        else
            return date_create($value)->format("d.m.Y H:i");
    }

    public function getBlockedTypeNamedAttribute()
    {
        return $this->vks_type_blocked ? "Упрощенные" : 'Стандартные';
    }

}
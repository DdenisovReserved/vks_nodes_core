<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class TechSupportRequest extends Eloquent
{
    const STATUS_WAIT_VKS_DECISION = 0;

    const STATUS_READY_FOR_SEND = 1;

    const STATUS_DELIVERED = 2;

    const STATUS_USER_REFUSE = 3;

    const STATUS_VKS_REFUSED = 4;

    //qty limits
    const USER_CREATED_CALLS_QTY_LIMIT = 3;
    const VKS_CALLS_QTY_LIMIT = 10;

    protected $table = 'tech_support_requests';

    protected $appends = array('status_humanized', 'is_applyable', 'status_label');

    protected $fillable = [
        'att_id',
        'user_message',
        'vks_id',
        'status',
        'owner_id',
        'tech_supportable'
    ];

    public function getIsApplyableAttribute()
    {
        if (date_create() < date_create($this->vks->end_date_time)
            && in_array($this->vks->status, array(VKS_STATUS_APPROVED, VKS_STATUS_PENDING))
            && !$this->vks->is_simple
        ) {
            return true;
        } else {
            return false;
        }
    }

    public function attendance()
    {
        return $this->hasOne('Attendance', 'id', 'att_id');
    }

    public function vks()
    {
        return $this->hasOne('Vks', 'id', 'vks_id');
    }

    public function owner()
    {
        return $this->hasOne('User', 'id', 'owner_id');
    }

    public function getStatusHumanizedAttribute()
    {
        $result = 'Статус неизвестен';
        switch ($this->status) {
            case(self::STATUS_WAIT_VKS_DECISION):
                $result = "Ожидает решения по ВКС";
                break;
            case(self::STATUS_READY_FOR_SEND):
                $result = "Готова к отправке";
                break;
            case(self::STATUS_DELIVERED):
                $result = "Отправлено в SM";
                break;
            case(self::STATUS_USER_REFUSE):
                $result = "Отозвана";
                break;
        }
        return $result;
    }

    public function getStatusLabelAttribute()
    {
        $result = "<span class='label label-as-badge label-";
        switch ($this->status) {
            case(self::STATUS_WAIT_VKS_DECISION):
                $class = 'default';
                $title = 'Ожидает решения Администратора ВКС';
                $text = 'Ожидает';
                break;
            case(self::STATUS_READY_FOR_SEND):
                $class = 'info';
                $title = "Готова к отправке";
                $text = 'Отправка';
                break;
            case(self::STATUS_DELIVERED):
                $class = 'success';
                $title = "Отправлена в SM";
                $text = 'Отправлена';
                break;
            case(self::STATUS_USER_REFUSE):
                $class = 'danger';
                $title = "Отозвана пользователем";
                $text = 'Отозвана';
                break;
            case(self::STATUS_VKS_REFUSED):
                $class = 'danger';
                $title = "Отказ в проведении ВКС";
                $text = 'Отказ ВКС';
                break;
            default:
                $class = 'default';
                $title = "Не отпределен";
                $text = 'Не отпределен';
                break;
        }
        $result .= $class."' title='".$title."'>".$text."</span>";
        return $result;
    }

    public function scopeMy($query)
    {
        return $query->where('owner_id', App::$instance->user->id);
    }

    public static function boot()
    {
        parent::boot();
        parent::observe(new TsCallObserver());
    }

}
<?php
//define order/limit/offset
use Violin\Violin;

trait validatorTrait
{
    public $validator;

    public function initValidator()
    {
        $this->validator = new Violin();
        $this->validator->addRuleMessage('required', "Поле <b>{field}</b> обязательно для заполнения");
        $this->validator->addRuleMessage('matches', 'Поле <b>{field}</b> должно совпадать с полем <b>{$0}</b>');
        $this->validator->addRuleMessage('int', 'Поле <b>{field}</b> должно содержать только цифры');
        $this->validator->addRuleMessage('email', "Поле <b>{field}</b> должно иметь корректный формат адреса электронной почты(во <b>внутреннем</b> сегменте банка), например SBT-Tomarov-IV<b>@mail.ca.sbrf.ru</b> ");
        $this->validator->addRuleMessage('pwd', 'Поле {field} должно содержать только латинские буквы, цифры или спецсимволы(!@#$%*). Минимум 5 символов');
        $this->validator->addRule('pwd', function ($value, $input, $args) {
            $result = false;
            if (preg_match("/^[0-9A-Za-z!@#$%*]{4,}$/", $value)) {
                $result = true;
            }
            return $result;
        });

        $this->validator->addRuleMessage('userFree', 'Пользователь с именем <b>{value}</b> уже существует в системе');
        $this->validator->addRule('userFree', function ($value, $input, $args) {
            $userCount = User::where('login', strtoupper($value))->count();
            return $userCount ? false : true;
        });

        $this->validator->addRuleMessage('attendance_is_tech_supportable', 'На выбранной точке ВКС, тех. поддержка не оказывается, извините');
        $this->validator->addRule('attendance_is_tech_supportable', function ($value, $input, $args) {
            try {
                $att = Attendance::findOrFail($value);
            } catch (Exception $e) {
               return false;
            }

            return $att->tech_supportable ? true : false;
        });


    }
}
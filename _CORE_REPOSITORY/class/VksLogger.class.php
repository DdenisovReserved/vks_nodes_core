<?php

class VksLogger extends Controller
{
    use validatorTrait;

    function logWrite($event_type, $content, $from_ip = "127.0.0.1", $by_user = 3)
    {
        //define user_id
        $by_user = isset(App::$instance->user->id) ? App::$instance->user->id : 3;
        //define ip
        $from_ip = App::$instance->user->ip;

        $this->validator->validate([
            'event_type' => [$event_type, 'between(1,25)'],
            'content' => [$content, 'max(8000)'],
        ]);
        //if no passes
        if (!$this->validator->passes()) {
            //trim string if it's lenght more than 8000
            $content = substr($content, 0, 7999);
        }
        $logRecord = new LogRecord();
        $logRecord->event_type = $event_type;
        $logRecord->from_ip = $from_ip;
        $logRecord->by_user = $by_user;
        $logRecord->content = $content;
        $logRecord->save();

    }
}
<?php
ini_set('SMTP', 'smtp.sbrf.ru');
ini_set("max_execution_time", 57);
class Mail
{
    const ADDRESS = 'videoconf@mail.ca.sbrf.ru';

    static function sendMailToStack($adress, $theme, $message)
    {
        //deploy headers
//        ini_set('max_execution_time',90);
//        $headers = 'MIME-Version: 1.0' . "\r\n";
//        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
//        $headers .= 'From: VideoConf@mail.ca.sbrf.ru' . "\r\n";
//        //prependStyles
//        $messageCss = "<style>";
////        $messageCss .= file_get_contents("css/mails/mailbootstrap.css");
////        $messageCss .= file_get_contents("css/core.css");
//        $messageCss .= file_get_contents("css/mails/core.css");
//        $messageCss .= "</style>";
////        ST::makeDebug($messageCss.$message);
        $mailCtrl = new MailStack_controller();
        $mailCtrl->put($adress, $theme, $message);
//        @mail($adress, $theme,  $messageCss.$message, $headers);

    } // function end

    static function sendMail($adress, $theme, $message)
    {
        ini_set('max_execution_time', 90);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: VideoConf@mail.ca.sbrf.ru' . "\r\n";
        //prependStyles
        $messageCss = "<style>";
        $messageCss .= file_get_contents("css/mails/core.css");
        $messageCss .= "</style>";
//        ST::makeDebug($messageCss.$message);
//        $mailCtrl = new mail_stack_controller();
//        $mailCtrl->put($adress, $theme, $message);
        mail($adress, $theme, $messageCss . $message, $headers);
        return true;

    }

    static function showMail($adress, $theme, $message)
    {
        ini_set('max_execution_time', 90);
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: VideoConf@mail.ca.sbrf.ru' . "\r\n";
        //prependStyles
        $messageCss = "<style>";
        $messageCss .= file_get_contents("css/mails/core.css");
        $messageCss .= "</style>";
//        ST::makeDebug($messageCss.$message);
//        $mailCtrl = new mail_stack_controller();
//        $mailCtrl->put($adress, $theme, $message);
        $message = $messageCss . $message;
        return compact('headers', 'adress', 'theme', 'message');


    }

    static function sendIcalEvent($vks, $method = 0, $requested_user) //humanized
    {
        $methods = array(
            0 => 'REQUEST',
            1 => 'REQUEST',
            2 => 'CANCEL'
        );
        //Create Email Headers
        $mime_boundary = "----Meeting Booking----" . MD5(TIME());
        $headers = "From: Vks_robot<" . self::ADDRESS . ">\n";
        $headers .= "Reply-To: Vks_robot <" . self::ADDRESS . ">\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";
        $headers .= "Content-class: urn:content-classes:calendarmessage\n";

        //Create Email Body (HTML)
        $message = "--$mime_boundary\r\n";
        $message .= "Content-Type: text/html; charset=UTF-8\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= "<html>\n";
        $message .= "<body>\n";
        $message .= '<p>ВКС #' . $vks->id . '</p>';
        $message .= '<p>Тема: ' . $vks->title . '</p>';
        $message .= '<p>Дата/Время: ' . $vks->humanized->date . ', ' . $vks->humanized->startTime . '-' . $vks->humanized->endTime . '</p>';
        $message .= '<p>Код подключения: ';
        if ($vks->connection_codes && $method != OutlookCalendarRequest::REQUEST_TYPE_DELETE) {
            $message .= '<ul>';
            foreach ($vks->connection_codes as $code) {
                if ($vks->owner && $requested_user->id == $vks->owner->id) {
                    $message .= '<li>' . $code->value_raw . '<sup>' . $code->tip . '</sup></li>';
                } else {
                    $message .= '<li>' . $code->value . '<sup>' . $code->tip . '</sup></li>';
                }

            }
            $message .= '</ul>';
        } else {
            $message .= 'Код не выдан или не требуется';
        }

        $message .= '</p>';
        if ($vks->owner) {
            $message .= '<p>Владелец ВКС: ' . $vks->owner->login;
            if ($vks->owner->fio)
                $message .= '(' . $vks->owner->fio . ', ' . $vks->owner->phone . ')';
            $message .= '</p>';
        }
        $message .= '<p>Ссылка на ВКС в Планировщике: ' . ST::linkToVksPage($vks->id) . '</p>';
        $message .= "</body>\n";
        $message .= "</html>\n";
        $message .= "--$mime_boundary\r\n";
        $behalfof =  $vks->owner ? $vks->owner->fio : '';
        $ical = 'BEGIN:VCALENDAR' . "\r\n" .
            'PRODID:-//Microsoft Corporation//Outlook 10.0 MIMEDIR//EN' . "\r\n" .
            'VERSION:2.0' . "\r\n" .
            'METHOD:' . $methods[$method] . "\r\n" .
            'BEGIN:VEVENT' . "\r\n" .
            'ORGANIZER;CN="Vks_robot_on_behalf_of '.$behalfof.'":MAILTO:' . self::ADDRESS . "\r\n" .
            'ATTENDEE;CN="' . $requested_user->fio . '";ROLE=REQ-PARTICIPANT;RSVP=TRUE:MAILTO:' . $requested_user->email . "\r\n" .
            'LAST-MODIFIED:' . date("Ymd\TGis") . "\r\n" .
            // 'UID:'.date("Ymd\TGis", strtotime($startTime)).rand()."@".$domain."\r\n" .
            'UID:' . md5($vks->id . App::$instance->main->appkey) . "\r\n" .
            'DTSTAMP:' . date("Ymd\TGis") . "\r\n" .
            'DTSTART:' . date("Ymd\THis", $vks->start_date_time->getTimestamp()) . "\r\n" .
            'DTEND:' . date("Ymd\THis", $vks->end_date_time->getTimestamp()) . "\r\n" .
            'TRANSP:OPAQUE' . "\r\n" .
            'SEQUENCE:' . date_create()->getTimestamp() . "\r\n" .
            'SUMMARY:' . $vks->title . "\r\n" .
//      'LOCATION:' . $location . "\r\n" .
            'CLASS:PUBLIC' . "\r\n" .
            'PRIORITY:5' . "\r\n" .
            'BEGIN:VALARM' . "\r\n" .
            'TRIGGER:-PT15M' . "\r\n" .
            'ACTION:DISPLAY' . "\r\n" .
            'DESCRIPTION:Reminder' . "\r\n" .
            'END:VALARM' . "\r\n" .
            'END:VEVENT' . "\r\n" .
            'END:VCALENDAR' . "\r\n";

        if ($method == OutlookCalendarRequest::REQUEST_TYPE_DELETE) {
            $ical .= 'STATUS:CANCELLED' . "\r\n";
        }

        $message .= 'Content-Type: text/calendar;name="meeting.ics";method=REQUEST' . "\n";
        $message .= "Content-Transfer-Encoding: 8bit\n\n";
        $message .= $ical;
        switch ($method) {
            case(0):
                $title = 'Приглашение на ВКС #' . $vks->id . ', ' . $vks->title;
                break;
            case(1):
                $title = 'изменение в ВКС #' . $vks->id . ', ' . $vks->title;
                break;
            case(2):
                $title = 'аннулирована ВКС #' . $vks->id . ', ' . $vks->title;
                break;
        }
        $mailsent = mail($requested_user->email, $title, $message, $headers);

        return ($mailsent) ? (true) : (false);
    }

} // class end

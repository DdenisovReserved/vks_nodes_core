<?php
//
//$date      = '20151015';
//$startTime = '1300';
//$endTime   = '1400';
//$subject   = 'test';
//$desc      = 'some test';
//
//$ical = "BEGIN:VCALENDAR
//VERSION:2.0
//PRODID:-//hacksw/handcal//NONSGML v1.0//EN
//BEGIN:VEVENT
//UID:" . md5(uniqid(mt_rand(), true)) . "example.com
//DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z
//DTSTART:".$date."T".$startTime."00Z
//DTEND:".$date."T".$endTime."00Z
//SUMMARY:".$subject."
//DESCRIPTION:".$desc."
//END:VEVENT
//END:VCALENDAR";
//
////set correct content-type-header
//header('Content-type: text/calendar; charset=utf-8');
//header('Content-Disposition: inline; filename=calendar.ics');
//echo $ical;
//exit;

/**
* sends an iCal event mail
* @param Timestamp $tsStart - timestart of the start time
* @param Timestamp $tsEnd - timestamp of end date
* @param String $location - location of event
* @param String $summary - event summary
* @param String $to - list of email recipients
* @param String $subject - email subject
*/


define("ORGANISATIONREPLYMAIL","videoconf@mail.ca.sbrf.ru");
define("ORGANISATIONNAME","VIMS VKS");

private function sendCalEntry( $tsStart, $tsEnd, $location, $summary, $title, $resources, $to, $subject ){

$from = ORGANISATIONREPLYMAIL;
$dtstart = date('Ymd',$tsStart).'T'.date('His',$tsStart);
$dtend = date('Ymd',$tsEnd).'T'.date('His',$tsEnd);
$loc = $location;

$vcal = "BEGIN:VCALENDAR\r\n";
$vcal .= "VERSION:2.0\r\n";
$vcal .= "PRODID:-//nonstatics.com//OrgCalendarWebTool//EN\r\n";
$vcal .= "METHOD:REQUEST\r\n";
$vcal .= "BEGIN:VEVENT\r\n";
//$vcal .= "ORGANIZER;CN=\"".ORGANISATIONNAME." (".$_SESSION['username'].")"."\":mailto:".ORGANISATIONREPLYMAIL."\r\n";
$vcal .= "ORGANIZER;CN=\"".ORGANISATIONNAME." (test_user)"."\":mailto:".ORGANISATIONREPLYMAIL."\r\n";
$vcal .= "UID:".date('Ymd').'T'.date('His')."-".rand()."-nonstatics.com\r\n";
$vcal .= "DTSTAMP:".date('Ymd').'T'.date('His')."\r\n";
$vcal .= "DTSTART:$dtstart\r\n";
$vcal .= "DTEND:$dtend\r\n";
$vcal .= "LOCATION:$location\r\n";
$vcal .= "SUMMARY:$summary\r\n";
$vcal .= "DESCRIPTION:Hinweis/Fahrer:$summary - Folgende Resourcen wurden gebucht: $resources \r\n";
$vcal .= "BEGIN:VALARM\r\n";
$vcal .= "TRIGGER:-PT15M\r\n";
$vcal .= "ACTION:DISPLAY\r\n";
$vcal .= "DESCRIPTION:Reminder\r\n";
$vcal .= "END:VALARM\r\n";
$vcal .= "END:VEVENT\r\n";
$vcal .= "END:VCALENDAR\r\n";

$headers = "From: $from\r\nReply-To: $from";
$headers .= "\r\nMIME-version: 1.0\r\nContent-Type: text/calendar; name=calendar.ics; method=REQUEST; charset=\"iso-8859-1\"";
$headers .= "\r\nContent-Transfer-Encoding: 7bit\r\nX-Mailer: Microsoft Office Outlook 12.0";

return @mail($to, $subject . " " . $summary . " / " . $resources, $vcal, $headers);
}
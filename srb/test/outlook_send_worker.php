<?php

include_once(__DIR__ . '/credentials.php'); //rabbitmq query

require_once('../config/_definitions.php'); //load main node config
require_once(CORE_REPOSITORY_REAL_PATH.'config/_version.php'); //version
require_once(CORE_APP_PATH . 'config/_definitions.php'); //central planner Load
require_once(NODE_REAL_PATH . 'config/config.php'); //local config
require_once(NODE_REAL_PATH . 'config/autoloader.php'); //local autoloader
require_once(CORE_REPOSITORY_REAL_PATH .'vendor/autoload.php'); //vendor
$app = App::get_instance($params);
require_once(NODE_REAL_PATH.'init.php');
$app->user = new Auth();
$app->node = MY_NODE;
//------------------------ !main load end!------------------

ini_set("max_execution_time", 0);

$connection = new AMQPConnection($credentials);
$connection->connect();
//Create and declare channel
$channel = new AMQPChannel($connection);
//AMQPC Exchange is the publishing mechanism
$vc = new Vks_controller();

$callback_func = function(AMQPEnvelope $message, AMQPQueue $q) use (&$max_consume,$vc) {
    $data = json_decode($message->getBody());

    print('---------------query--------------'."\r\n");
    echo sprintf(" QueueName: %s", $q->getName()), PHP_EOL;
    print('---------------received!--------------'."\r\n");
    try {
        $vks = Vks::approved()->notEnded()->findOrFail($data->vks_id);
        $vc->humanize($vks);
        $user = User::find($data->user_id);

        if (!OutlookCalendarRequest::where('user_id', $user->id)->where('vks_id', $vks->id)->count()) {
            $reSend = OutlookCalendarRequest::create(array(
                'user_id' => $user->id,
                'vks_id' => $vks->id,
                'request_type' => $data->request_type,
                'send_status' => OutlookCalendarRequest::SEND_STATUS_COMPLETED
            ));
            App::$instance->log->logWrite(LOG_OTHER_EVENTS, "New Outlook request create for " . $user->id . ', vks: ' . $vks->id);
        } else {
            if ($data->force) {
                $reSend = OutlookCalendarRequest::where('user_id', $user->id)->where('vks_id', $vks->id)->first();
                $reSend->request_type = $data->request_type;
                $reSend->send_status = OutlookCalendarRequest::SEND_STATUS_COMPLETED;
                $reSend->save();
                App::$instance->log->logWrite(LOG_OTHER_EVENTS, "New Outlook request create for " . $user->id . ', vks: ' . $vks->id);
            }  else {
                echo sprintf("Outlook Request to: user_id %s, and vks_id %s already sended, no force KEY detected", $data->user_id, $data->vks_id), PHP_EOL;
            }
        }

        Mail::sendIcalEvent($vks, $reSend->request_type, $user);
        App::$instance->log->logWrite(LOG_MAIL_SENDED, 'Outlook event invite sended to: ' . $user->email);
    } catch(Exception $e) {
        echo sprintf("Outlook Request to: user_id %s, and vks_id %s broken", $data->user_id, $data->vks_id), PHP_EOL;
    }

    $q->ack($message->getDeliveryTag());

    print('---------------ready!--------------'."\r\n");
};
try{
    $queue = new AMQPQueue($channel);
    $queue->setName($routing_key);
    $queue->setFlags(AMQP_DURABLE);
    $queue->declareQueue();
    echo ' [*] Waiting for messages. To exit press CTRL+C ', PHP_EOL;
    $queue->consume($callback_func, AMQP_DURABLE, 'fetcher');
}catch(AMQPQueueException $ex){
    print_r($ex);
}catch(Exception $ex){
    print_r($ex);
}
echo 'Close connection...', PHP_EOL;
$queue->cancel();
$connection->disconnect();
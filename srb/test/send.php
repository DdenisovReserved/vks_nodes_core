<?php
require_once __DIR__ . '/../../_CORE_REPOSITORY/vendor/autoload.php';
//Establish connection to AMQP
include_once(__DIR__ . '/../../_CORE_REPOSITORY/config/AMQ_credentials.php');
$connection = new AMQPConnection($credentials);
$connection->connect();
//Create and declare channel
$channel = new AMQPChannel($connection);
$channel->setPrefetchCount(1);

$routing_key = 'vks_to_outlook_messages';

try {
    $queue = new AMQPQueue($channel);
    $queue->setName($routing_key);
    $queue->setFlags(AMQP_DURABLE);
    $queue->declareQueue();
} catch (Exception $ex) {
    print_r($ex);
};

$c = 1;
while ($c < 200000) {
    $message = json_encode(array(
            'force'=>false,
            'user_id'=>43,
            'vks_id'=>rand(60,65)
        )
    );
    $exchange = new AMQPExchange($channel);
    $exchange->publish($message, $routing_key);
    echo " [x] Sent {$message}", PHP_EOL;
    $c++;
}


$connection->disconnect();


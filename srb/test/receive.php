<?php
ini_set("max_execution_time", 0);
//Establish connection AMQP
include_once(__DIR__ . '/credentials.php');

$connection = new AMQPConnection($credentials);
$connection->connect();
//Create and declare channel
$channel = new AMQPChannel($connection);
//AMQPC Exchange is the publishing mechanism
$routing_key = 'vks_to_outlook_messages';

$callback_func = function(AMQPEnvelope $message, AMQPQueue $q) use (&$max_consume) {
    $data = json_decode($message->getBody());
    print('---------------query--------------'."\r\n");
    echo sprintf(" QueueName: %s", $q->getName()), PHP_EOL;
    print('---------------received!--------------'."\r\n");
    var_dump($data->test);
    $q->ack($message->getDeliveryTag());
    print('---------------working--------------'."\r\n");
    sleep(1);
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
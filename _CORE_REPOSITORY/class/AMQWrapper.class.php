<?php

class AMQWrapper
{
    private $connection = null;
    private $channel = null;
    private $standart_ctrl = null;

    /*example
     * $credentials = array(
        'host' => 'localhost',
        'port' => 5672,
        'login' => 'guest',
        'password' => 'guest'
    );
     */
    function __construct(array $credentials)
    {
        $this->standart_ctrl = new Controller();
        try {
            $this->connection = new AMQPConnection($credentials);
            $this->connection->connect();
            //Create and declare channel
            $this->channel = new AMQPChannel($this->connection);
            $this->channel->setPrefetchCount(1);
        } catch (Exception $e) {

            $this->standart_ctrl->error('500', 'AMQ server unreachable');
        }
    }

    public function pushMessage($queueName, array $message)
    {
        try {
            $queue = new AMQPQueue($this->channel);
            $queue->setName($queueName);
            $queue->setFlags(AMQP_DURABLE);
            $queue->declareQueue();
        } catch (Exception $ex) {
            $this->standart_ctrl->error('500', 'AMQ channel unreachable');

        };

        $message = json_encode($message);
        $exchange = new AMQPExchange($this->channel);
        $exchange->publish($message, $queueName);

    }

    public function consume($queueName, $callBackFunction)
    {
        try {
            $queue = new AMQPQueue($this->channel);
            $queue->setName($queueName);
            $queue->setFlags(AMQP_DURABLE);
            $queue->declareQueue();
            echo ' [*] Waiting for messages on ' . $queueName . '. To exit press CTRL+C ', PHP_EOL;
            $queue->consume($callBackFunction);
        } catch (AMQPQueueException $ex) {
            print_r($ex);
        } catch (Exception $ex) {
            print_r($ex);
        }
    }


    public function disconnect()
    {
        $this->connection->disconnect();
    }
}
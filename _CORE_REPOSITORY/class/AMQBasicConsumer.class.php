<?php

abstract class AMQBasicConsumer
{
    function callback(AMQPEnvelope $message, AMQPQueue $q) {
        //say that message received
        $q->ack($message->getDeliveryTag());
    }

}
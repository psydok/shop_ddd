<?php


namespace app\modules\api\services\brokers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class SenderMessages
{
    public function execute(string $message)
    {
        $queue = $_ENV['RABBIT_QUEUE'];
        $connection = new AMQPStreamConnection(
            $_ENV['RABBIT_HOST'],
            $_ENV['RABBIT_PORT'],
            $_ENV['RABBIT_USER'],
            $_ENV['RABBIT_PASSWORD']
        );

        $channel = $connection->channel();
        $channel->queue_declare(
            $queue,
            false,
            true, # чтобы не потерять сообщения
            false,
            false);

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, '', $queue);
        $channel->close();
        $connection->close();
    }

}
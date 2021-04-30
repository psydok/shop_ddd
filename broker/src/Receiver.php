<?php

namespace brokers;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
require_once __DIR__ . '/../vendor/autoload.php';

//include __DIR__ . '/../objects/links.php';
//define('INSTANCE_NAME', getenv('INSTANCE_NAME'));
//define('AMQP_DEBUG', true);

class Receiver
{
    public function listen()
    {
//        $dotenv = new Dotenv();
//        $dotenv->load(__DIR__ . '/../config/.env');

        $redis = new Redis() or die("Cannot load Redis module.");
        try {
            $redis->connect(
                $_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }

        $queue = $_ENV['RABBIT_QUEUE'];
        try {
            $connection = new AMQPStreamConnection(
                $_ENV['RABBIT_HOST'],
                $_ENV['RABBIT_PORT'],
                $_ENV['RABBIT_USER'],
                $_ENV['RABBIT_PASSWORD']
            );
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
        $channel = $connection->channel();

        $channel->queue_declare(
            $queue,
            false,
            true, # чтобы не потерять сообщения
            false,
            false);
        $callback = function ($msg) use ($redis) {
            echo "\n", '[x] Received ', $msg->body, "\n";

            $object = json_decode($msg->body);
            $url = $object->{'link'};
            $cache_key = 'url-status_' . $url;
            $result = $redis->get($cache_key);
            if ($result) {
                echo $result . "\n";
            } else {
                if (!$object) {
                    echo " !!!THIS IS NOT JSON ON RECEIVER!!! ";
                    $msg->delivery_info['channel']->basic_cancel('');
                    return;
                } else {
                    if (strlen($_ENV["UNI_PROXY"]) > 0) {
                        $context = array(
                            'http' => array(
                                'proxy' => "tcp://" . $_ENV["UNI_PROXY"],
                                'request_fulluri' => true
                            ),
                        );
                        file_get_contents($url, False, stream_context_create($context));
                    } else {
                        file_get_contents($url);
                    }
                    // get status
                    $result = $http_response_header[0];
                    // create cache key-value
                    $redis->set($cache_key, (string)$result, 120);
                }
            }

            $data_status = json_encode([
                "status" => $result
            ]);

            // send update row in db
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://"
                    . $_ENV["NGINX_HOST"] . ":" . $_ENV["NGINX_PORT_INT"]
                    . "/api/links/" . $object->{'link_id'},
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => $data_status,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            echo $response . "\n";

            // notify subscribe $_ENV['REDIS_HOST']
            // that the response has been successfully sent to the db
            $redis->publish(
                $_ENV['REDIS_HOST'],
                json_encode([
                    'send-test' => 'success'
                ])
            );
        };

        $channel->basic_consume(
            $queue,
            '',
            false,
            false,
            false,
            false,
            $callback);

        while (count($channel->callbacks)) {
            try {
                $channel->wait();
            } catch (AMQPTimeoutException $exception) {
                echo $exception->getMessage();
            }
        }
        $channel->close();
        $connection->close();
        $redis->close();
    }
}

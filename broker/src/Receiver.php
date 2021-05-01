<?php

namespace brokers;

require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Driver\Manager;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;


//define('INSTANCE_NAME', getenv('INSTANCE_NAME'));
//define('AMQP_DEBUG', true);

class Receiver
{
    private function getStruct($items)
    {
        $itemsByIdCategory = [];
        $itemsByCategories = [];
        foreach ($items as $item) {
            $itemsByIdCategory[$item['category_id']][] = $item;
            var_dump($item['category_id']);
        }
        foreach (array_keys($itemsByIdCategory) as $key) {
            //$category = CategoryRecord::findOne(['id' => $key])->getAttributes();
            $category['items'] = $itemsByIdCategory[$key];
            $itemsByCategories[] = $category;
        }
        return $itemsByCategories;
    }

    public function listen()
    {
        $user = $_ENV['MONGODB_USER'];
        $pwd = $_ENV['MONGODB_PASSWORD'];
        $host = $_ENV['MONGODB_HOST'];
        $port = $_ENV['MONGODB_PORT'];
        $db = $_ENV['MONGODB_DB'];
        $connect = "mongodb://${host}:${port}/";

        try {
            $collection = (new Client($connect,[
                'username' => $user,
                'password' => $pwd,
                'ssl' => true,
                'authSource' => $user,
            ], ))->test->users;
            $insertOneResult = $collection->insertOne([
                'username' => 'admin',
                'email' => 'admin1@example.com',
                'name' => 'Admin User',
            ]);
            printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());
            echo $insertOneResult->getInsertedId();
            $manager = new Manager($connect);
            $r = $manager->executeCommand($db, new \MongoDB\Driver\Command(['ping' => 1]));
            echo $r->isDead();
//            var_dump($manager);
//
//            $collection = (new Client)->test->users;
//

//            printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());
//            echo $insertOneResult->getInsertedId();

            $queue = $_ENV['RABBIT_QUEUE'];
            $connection = new AMQPStreamConnection(
                $_ENV['RABBIT_HOST'],
                $_ENV['RABBIT_PORT'],
                $_ENV['RABBIT_USER'],
                $_ENV['RABBIT_PASSWORD']
            );

        } catch (\Exception $e) {
            echo $e->getMessage() . '\n';

            echo $e->getLine() . '\n';
            echo $e->getTraceAsString() . '\n';

            die;
        }

        $channel = $connection->channel();

        $channel->queue_declare(
            $queue,
            false,
            true, # чтобы не потерять сообщения
            false,
            false);
        $items = [];
        $callback = function ($json) use ($items) {
            echo "\n", '[x] Received ', $json->body, "\n";
            $object = json_decode($json->body, true);

            if (!$object) {
                echo " !!!THIS IS NOT JSON ON RECEIVER!!! ";
                $json->delivery_info['channel']->basic_cancel('');
                return;
            }

            $jsonIterator = new RecursiveIteratorIterator(
                new RecursiveArrayIterator($object),
                RecursiveIteratorIterator::SELF_FIRST);

            foreach ($jsonIterator as $key => $val) {
                switch ($key) {
                    case 'insert':
                        break;
                    case 'update':
                        break;
                    case 'delete':
                        break;
                    default:
                        break;
                }
//                if(is_array($val)) {
//                    echo "$key:\n";
//                } else {
//                    echo "$key => $val\n";
//                }
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL =>
                    "http://"
                    . $_ENV["NGINX_HOST"] . ":" . $_ENV["NGINX_PORT_INT"]
                    . "/api/v1/categories/" . $object->{'link_id'},
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "PUT",
                CURLOPT_POSTFIELDS => [],
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            echo $response . "\n";
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
    }
}

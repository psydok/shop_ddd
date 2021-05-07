<?php

namespace brokers;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DocumentCategory.php';
require_once __DIR__ . '/StructItem.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Sokil\Mongo\Client;
use brokers\models\DocumentCategory;
use brokers\models\StructItem;

class CUDReceiver
{
    public function listen()
    {
        try {
            $queue = $_ENV['RABBIT_QUEUE'];
            $connection = new AMQPStreamConnection(
                $_ENV['RABBIT_HOST'],
                $_ENV['RABBIT_PORT'],
                $_ENV['RABBIT_USER'],
                $_ENV['RABBIT_PASSWORD']
            );

            $host = $_ENV['MONGODB_HOST'];
            $port = $_ENV['MONGODB_PORT'];
            $hostnames = "mongodb://${host}:${port}";

            $client = new Client($hostnames, [
                'username' => $_ENV['MONGODB_USER'],
                'password' => $_ENV['MONGODB_PASSWORD']
            ]);
            $client->useDatabase($_ENV['MONGODB_DB']);
            $collection = $client->getCollection($_ENV['MONGODB_CATALOG']);


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

        $callback = function ($json) use ($collection) {
            echo "\n", '[x] Received ', $json->body, "\n";

            $object = json_decode($json->body, true);
            if (empty($object)) {
                echo "[x] !!!THIS IS NOT JSON ON RECEIVER!!! ";
                $json->delivery_info['channel']->basic_cancel('');
                return;
            }

            $action = '';
            $name_obj = '';
            foreach (['item', 'category'] as $word) {
                if (array_key_exists($word, $object)) {
                    $name_obj = $word;
                    break;
                }
            }

            foreach (['insert', 'update', 'delete'] as $word) {
                if (array_key_exists($word, $object[$name_obj])) {
                    $action = $word;
                    break;
                }
            }

            $data = $object[$name_obj][$action];
            switch ($action) {
                case 'insert':
                    if ($name_obj == 'category') {
                        $documentCategory = new DocumentCategory($collection);
                        $documentCategory->id = $data['id'];
                        $documentCategory->name = $data['name'];
                        $res = $documentCategory->save();
                    } else {
                        $item = new StructItem([
                            'id' => $data['id'],
                            'name' => $data['name'],
                            'price' => $data['price'],
                            'img_link' => $data['img_link']
                        ]);
                        $documentCategory = $collection->find()->where('id', $data['category_id']['id'])->findOne();
                        $documentCategory->push('items', $item);
                        $res = $documentCategory->save();
                    }
                    echo '[x] Inserted #' . $res . '\n';
                    break;
                case'update':
                    if ($name_obj == 'category') {
                        $documentCategory = $collection->find()->where('id', $data['id'])->findOne();
                        $documentCategory->name = $data['name'];
                        $documentCategory->save();
                    } else {
                        $documentCategory = $collection->find()->where('id', $data['category_id']['id'])->findOne();
                        $items = $documentCategory->getItems();
                        foreach ($items as $item) {
                            if ($item['id'] === $data['id']) {
                                $updatedItem = new StructItem($item);
                                $updatedItem->setName($data['name']);
                                $updatedItem->setPrice((float)$data['price']);
                                $updatedItem->setImgLink($data['img_link']);

                                $removeItem = array('$pull' => array(
                                    'items' => array(
                                        'id' => $data['id'],
                                    )
                                ));

                                $collection->update(['id' => $data['category_id']['id']],
                                    $removeItem,
                                    array("multiple" => true));

                                $documentCategory->push('items', $updatedItem);
                                $documentCategory->save();
                                break;
                            }
                        }
                    }
                    echo '[x] Updated \n';
                    break;
                case 'delete':
                    if ($name_obj == 'category') {
                        $documentCategory = $collection->find()->where('id', $data['id'])->findOne();
                        $documentCategory->delete();
                        $documentCategory->save();
                    } else {
                        $removeItem = array(
                            '$pull' => array(
                                'items' => array(
                                    'id' => $data['id'],
                                )
                            )
                        );
                        $collection->update(
                            ['id' => $data['category_id']['id']],
                            $removeItem,
                            array("multiple" => true));
                    }
                    echo '[x] Deleted \n';
                    break;
                default:
                    break;
            }
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

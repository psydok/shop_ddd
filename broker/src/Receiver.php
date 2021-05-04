<?php

namespace brokers;

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Sokil\Mongo\Client;

class Receiver
{
    private static function getStruct($arrItem)
    {
        $itemsByIdCategory = [];
        $itemsByCategories = [];
        foreach ($arrItem as $item) {
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

    private static function getIdMsg($dict, $arrItem)
    {
        foreach ($dict as $word) {
            if (array_key_exists($word, $arrItem)) {
                return $word;
            }
        }
        return '';
    }

    private static function getObject($arrItem): string
    {
        $objects = ['item', 'catalog'];
        return self::getIdMsg($objects, $arrItem);
    }

    private static function getAction($arrItem): string
    {
        $actions = ['insert', 'update', 'delete'];
        return self::getIdMsg($actions, $arrItem);
    }


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
            $user = $_ENV['MONGODB_USER'];
            $pwd = $_ENV['MONGODB_PASSWORD'];
            $host = $_ENV['MONGODB_HOST'];
            $port = $_ENV['MONGODB_PORT'];
            $db = $_ENV['MONGODB_DB'];
            $hostnames = "mongodb://${host}:${port}";
            $client = new Client($hostnames, [
                'username' => $user,
                'password' => $pwd
            ]);
            $client->useDatabase($db);
            $collection = $client->getCollection('catalog');
            include('Category.php');
            include('Item.php');

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
                        $category = new \Category($collection);
                        $category->id = $data['id'];
                        $category->name = $data['name'];
                        $res = $category->save();
                    } else {
                        $item = new \Item([
                            'id' => $data['id'],
                            'name' => $data['name'],
                            'price' => $data['price'],
                            'img_link' => $data['img_link']
                        ]);
                        $category = $collection->find()->where('id', $data['category_id']['id'])->findOne();
                        $category->push('items', $item);
                        $res = $category->save();
                    }
                    echo '[x] Inserted #' . $res . '\n';
                    break;
                case'update':
                    if ($name_obj == 'category') {
                        $category = $collection->find()->where('id', $data['id'])->findOne();
                        $category->name = $data['name'];
                        $category->save();
                    } else {
                        $category = $collection->find()->where('id', $data['category_id']['id'])->findOne();
                        $items = $category->getItems();
                        foreach ($items as $item) {
                            if ($item['id'] === $data['id']) {
                                $updatedItem = new \Item($item);
                                $updatedItem->setName($data['name']);
                                $updatedItem->setPrice((float)$data['price']);
                                if ($data['img_link'])
                                    $updatedItem->setImgLink($data['img_link']);

                                $removeItem = array('$pull' => array(
                                    'items' => array(
                                        'id' => $data['id'],
                                    )
                                ));

                                $collection->update(['id' => $data['category_id']['id']],
                                    $removeItem,
                                    array("multiple" => true));

                                $category->push('items', $updatedItem);
                                $category->save();
                                break;
                            }
                        }
                    }
                    echo '[x] Updated \n';
                    break;
                case 'delete':
                    if ($name_obj == 'category') {
                        $category = $collection->find()->where('id', $data['id'])->findOne();
                        $category->delete();
                        $category->save();
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

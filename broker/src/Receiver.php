<?php

namespace brokers;

require_once __DIR__ . '/../vendor/autoload.php';

use app\models\Category;
use app\models\Item;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;

class Receiver
{
    private function getStruct($arrItem)
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

    private function getIdMsg($dict, $arrItem)
    {
        foreach ($dict as $word) {
            if (array_key_exists($word, $arrItem)) {
                return $word;
            }
        }
        return '';
    }

    private function getObject($arrItem): string
    {
        $objects = ['item', 'catalog'];
        return $this->getIdMsg($objects, $arrItem);
    }

    private function getAction($arrItem): string
    {
        $actions = ['insert', 'update', 'delete'];
        return $this->getIdMsg($actions, $arrItem);
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
        $callback = function ($json) {
            echo "\n", '[x] Received ', $json->body, "\n";
            $object = json_decode($json->body, true);

            if (!$object) {
                echo "[x] !!!THIS IS NOT JSON ON RECEIVER!!! ";
                $json->delivery_info['channel']->basic_cancel('');
                return;
            }

            $user = $_ENV['MONGODB_USER'];
            $pwd = $_ENV['MONGODB_PASSWORD'];
            $host = $_ENV['MONGODB_HOST'];
            $port = $_ENV['MONGODB_PORT'];
            $db = $_ENV['MONGODB_DB'];
            $hostnames = "${host}:${port}";

            \Purekid\Mongodm\ConnectionManager::setConfigBlock('default', array(
                'connection' => array(
                    'hostnames' => $hostnames,
                    'database' => $db,
                    'username' => $user,
                    'password' => $pwd,
                    'options' => array()
                )
            ));

            $action = $this->getAction($object);
            $name_obj = $this->getObject($object);

            $data = $object[$name_obj][$action];
            switch ($action) {
                case 'insert':
                    if ($name_obj == 'category') {
                        $category = new Category();
                        $category->id = $data['id'];
                        $category->name = $data['name'];
                        echo $category->save();
                    } else {
                        $item = new Item();
                        $item->id = $data['id'];
                        $item->name = $data['name'];
                        $item->price = $data['price'];
                        $item->img_link = $data['img_link'];
                        $category = Category::find(['id' => $item['category_id']['id']]);
                        $category->add($item);
                        $category->save();
                        echo 111;
                    }
                    break;
                case'update':
                    if ($name_obj == 'category') {
                        $category = Category::find(['id'=>$data['id']]);
                        $category->name = $data['name'];
                        $category->save();
                    } else {
                        $item = Item::find(['id'=>$data['id']]);
                        $item->name = $data['name'];
                        $item->price = $data['price'];
                        $item->img_link = $data['img_link'];
                        $category = Category::find(['id' => $item['category_id']['id']]);
                        $category->add($item);
                        $category->save();
                        echo 111;
                    }
                    break;
                case 'delete':
                    if ($name_obj == 'category') {
                        $category = Category::find(['id'=>$data['id']]);
                        $category->delete();
                        $category->save();
                    } else {
                        $item = Item::find(['id'=>$data['id']]);
                        $item->delete();
                        $item->save();
                    }
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

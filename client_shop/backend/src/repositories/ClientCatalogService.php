<?php

namespace app\services;

use app\models\Category;

require_once __DIR__ . '/../../vendor/autoload.php';

class ClientCatalogService implements ClientServiceInterface
{
    public function __construct()
    {
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
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getItemsByIdCategory(int $id)
    {
        try {
            return Category::find(['id' => $id])->toArray();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        return null;
    }

    public function getCatalogsCategories()
    {
        try {
            return Category::all()->toArray();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
        return null;
    }
}
<?php

namespace app\services;
require_once __DIR__ . '/../../vendor/autoload.php';

use app\models\CatalogCollection;
use \Sokil\Mongo\Client;

class ClientCatalogService implements ClientServiceInterface
{
    private $collection;
    private static $returnedFields = ['id', 'name', 'items'];

    public function __construct()
    {
        $host = $_ENV['MONGODB_HOST'];
        $port = $_ENV['MONGODB_PORT'];
        $hostnames = "mongodb://${host}:${port}";

        $client = new Client($hostnames, [
            'username' => $_ENV['MONGODB_USER'],
            'password' => $_ENV['MONGODB_PASSWORD']
        ]);
        $client->useDatabase($_ENV['MONGODB_DB']);

        $this->collection = $client->getCollection($_ENV['MONGODB_CATALOG']);

    }

    /**
     * @param int $id
     * @return array|null
     */
    public function getItemsByIdCategory(int $id)
    {
        try {
            $documentCategory = $this->collection->find()
                ->fields(self::$returnedFields)
                ->where('_id', $id)
                ->findOne();
            return $documentCategory;
        } catch (\Exception $e) {
            echo var_dump($e->getMessage());
        }
        return null;
    }

    public function getCatalogsCategories()
    {
        try {
            $document = $this->collection->find()
                ->fields(self::$returnedFields)
                ->all();
            return $document;
        } catch (\Exception $e) {
            echo var_dump($e->getMessage());
        }
        return null;
    }
}
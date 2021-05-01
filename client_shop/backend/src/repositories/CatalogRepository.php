<?php

namespace app\client\repositories;

require_once __DIR__ . '../../vendor/autoload.php';

class CatalogRepository implements ClientRepositoryInterface
{
    private $db;

    public function __construct()
    {
        $this->db = new MongoDB\Client("mongodb://localhost:27017");
        $collection = $this->db->demo->beers;
    }


    public function getItemsByIdCategory()
    {
        // TODO: Implement getItemsByIdCategory() method.
    }
}
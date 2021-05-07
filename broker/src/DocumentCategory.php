<?php
namespace brokers\models;
require_once __DIR__ . '/../vendor/autoload.php';

class DocumentCategory extends \Sokil\Mongo\Document
{
    public function getItems()
    {
        return $this->getObjectList('items', '\StructItem');
    }

}
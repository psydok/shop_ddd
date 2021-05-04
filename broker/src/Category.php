<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Category extends \Sokil\Mongo\Document
{
    public function getItems()
    {
        return $this->getObjectList('items', '\Item');
    }

}
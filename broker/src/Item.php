<?php

use Purekid\Mongodm\Model;

require_once __DIR__ . '/../vendor/autoload.php';

class Item extends \Purekid\Mongodm\Model
{
    static $collection = "catalog";
    protected static $attrs = array(
        'id' => array( 'type' => Model::DATA_TYPE_INTEGER),
        'name' => array('type' => Model::DATA_TYPE_STRING),
        'price' => array('model' => Model::DATA_TYPE_STRING),
        'img_link' => array('model' => Model::DATA_TYPE_STRING),
    );
}
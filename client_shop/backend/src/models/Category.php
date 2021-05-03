<?php
namespace app\models;

use Purekid\Mongodm\Model;

require_once __DIR__ . '/../../vendor/autoload.php';

class Category extends \Purekid\Mongodm\Model
{
    static $collection = "catalog";
    protected static $attrs = array(
        'id' => array('type' => Model::DATA_TYPE_INTEGER),
        'name' => array('type' => Model::DATA_TYPE_STRING),
        'items' => array('model' => Item::class, 'type' => Model::DATA_TYPE_REFERENCES),
    );
}
<?php

namespace app\modules\api\repositories;

use app\modules\api\models\ItemEntity;
use app\modules\api\models\ItemRecord;
use Yii;
use yii\db\Connection;

class ItemRepository implements RepositoryInterface
{
//    private $db;

    public function __construct()
    {
//        $this->db = Yii::$app->getDb();
    }

    public static function getNewId(): int
    {
        return ItemRecord::maxId() + 1;
    }

    public function insert($object): void
    {
        try {
            $item = new ItemRecord();
            $item->id = $object->getId();
            $item->name = $object->getName();
            $item->category_id = $object->getCategoryId()->getId();
            $item->price = $object->getPrice();
            $item->img_link = $object->getImgLink();
            $item->save();
        } catch (\Throwable $e) {
        }
    }

    /**
     * @param ItemEntity $object
     */
    public function update($object): void
    {
        try {
            $item = ItemRecord::findOne(['id' => $object->getId()]);
            $item->name = $object->getName();
            $item->category_id = $object->getCategoryId()->getId();
            $item->price = $object->getPrice();
            $item->img_link = $object->getImgLink();
            $item->save();
        } catch (\Throwable $e) {
        }
    }

    public function selectById($id)
    {
        $item = ItemRecord::findOne(['id' => $id]);
        return $item;
    }

    public function select()
    {
        return ItemRecord::find()->all();
    }
}
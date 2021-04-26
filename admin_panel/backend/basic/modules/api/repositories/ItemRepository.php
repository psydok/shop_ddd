<?php

namespace app\modules\api\repositories;

use app\modules\api\models\ItemEntity;
use app\modules\api\models\ItemRecord;

class ItemRepository implements RepositoryInterface
{
    private $itemRecord;

    public function __construct(ItemRecord $itemRecord)
    {
        $this->itemRecord = $itemRecord;
    }

    public static function getNewId()
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

            if ($item->save()) {
//                return $object->getId();
            }
//            return $item->errors;
        } catch (\Throwable $e) {
        }
    }

    /**
     * @param ItemEntity $object
     */
    public function update($object): void
    {
        try {
            $item = $this->itemRecord::findOne(['id' => $object->getId()]);
            $item->name = $object->getName();
            $item->category_id = $object->getCategoryId()->getId();
            $item->price = $object->getPrice();
            $item->img_link = $object->getImgLink();

            if ($item->save()) {
//                return $object->getId();
            }
//            var_damp( $item->errors );
        } catch (\Throwable $e) {
        }
    }

    public function selectById($id)
    {
        $item = $this->itemRecord::findOne(['id' => $id]);
        return $item;
    }

    public function select()
    {
        return $this->itemRecord::find()->all();
    }
}
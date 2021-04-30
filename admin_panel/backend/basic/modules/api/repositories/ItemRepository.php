<?php

namespace app\modules\api\repositories;

use app\modules\api\models\ItemEntity;
use app\modules\api\models\ItemRecord;
use function app\modules\api\services\brokers\sendMessageInRabbit;

class ItemRepository implements RepositoryInterface
{
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

            sendMessageInRabbit(["insert" => $item->getAttributes()]);
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
            if (is_null($item))
                return;
            $item->name = $object->getName();
            $item->category_id = $object->getCategoryId()->getId();
            $item->price = $object->getPrice();
            $item->img_link = $object->getImgLink();
            $item->save();

            sendMessageInRabbit(["update" => $item->getAttributes()]);
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
        $items = ItemRecord::find()->all();
        return $items;
    }

    /**
     * @param ItemRecord $object
     */
    public function delete($object): void
    {
        $object->delete();

        sendMessageInRabbit(["delete" => $object->getAttributes()]);
    }
}


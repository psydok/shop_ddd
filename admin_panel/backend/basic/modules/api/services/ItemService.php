<?php

namespace app\modules\api\services;

use app\modules\api\models\CategoryEntity;
use app\modules\api\repositories\ItemRepository;
use app\modules\api\models\ItemEntity;

class ItemService implements ServiceInterface
{
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param array $json_item Item
     * @return array|string
     */
    public function create($json_item)
    {
        $category_json = $json_item['category_id'];
        $category = CategoryEntity::withID($category_json['id']);
        $newItem = ItemEntity::withFullProps(
            $this->itemRepository::getNewId(),
            $category,
            $json_item['name'],
            $json_item['price'],
            $json_item['img_link'] ?? null
        );

        return $this->itemRepository->insert($newItem);
    }

    public function getAll()
    {
        return $this->itemRepository->select();
    }

    /**
     * @param int $id
     * @return \app\modules\api\models\ItemRecord|null
     */
    public function getById($id)
    {
        return $this->itemRepository->selectById($id);
    }

    /**
     * @param $id
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteById($id)
    {
        try {
            $item = self::getById($id);
            if (is_null($item))
                return null;
            return $item->delete();
        } catch (\Throwable $e) {
            return null;
//            return $e->getMessage();
        }
    }

    /**
     * @param $json_item
     * @return array|mixed|string
     */
    public function update($json_item)
    {
        $category_json = $json_item['category_id'];

        $itemRecord = self::getById($json_item['id']);
        $item = ItemEntity::withFullProps(
            $itemRecord->id,
            CategoryEntity::withID($itemRecord->category_id),
            $itemRecord->name,
            $itemRecord->price,
            $itemRecord->img_link
        );

        $category = CategoryEntity::withID($category_json['id']);
        $item->setCategoryId($category);
        $item->setName($json_item['name']);
        $item->setPrice($json_item['price']);
        if (!empty($json_item['img_link']))
            $item->setImgLink($category_json['id']);

        return $this->itemRepository->update($item);
    }
}
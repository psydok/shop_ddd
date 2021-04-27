<?php

namespace app\modules\api\services;

use app\modules\api\dto\ItemDto;
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
     * @param ItemDto $dto
     */
    public function create($dto)
    {
        $category = CategoryEntity::withID($dto->category_id);
        $newItem = ItemEntity::withFullProps(
            $this->itemRepository::getNewId(),
            $category,
            $dto->name,
            $dto->price,
            $dto->img_link ?? null
        );

        $this->itemRepository->insert($newItem);
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
     * @param int $id
     */
    public function deleteById($id)
    {
        try {
            $item = self::getById($id);
            if (is_null($item))
                return;
            $item->delete();
        } catch (\Throwable $e) {
//            return $e->getMessage();
        }
    }

    /**
     * @param ItemDto $dto
     */
    public function update($dto)
    {
        $itemRecord = self::getById($dto->id);
        $item = ItemEntity::withFullProps(
            $itemRecord->id,
            CategoryEntity::withID($itemRecord->category_id),
            $itemRecord->name,
            $itemRecord->price,
            $itemRecord->img_link
        );

        $category = CategoryEntity::withID($dto->category_id);
        $item->setCategoryId($category);
        $item->setName($dto->name);
        $item->setPrice($dto->price);
        if (!empty($dto->img_link))
            $item->setImgLink($dto->img_link);

        $this->itemRepository->update($item);
    }
}
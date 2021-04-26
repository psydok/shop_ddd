<?php

namespace app\modules\api\services;

use app\modules\api\models\CategoryEntity;
use app\modules\api\repositories\CategoryRepository;

class CategoryService implements ServiceInterface
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param array $json_category
     * @return array|string
     */
    public function create($json_category)
    {
        $newCategory = CategoryEntity::withProps(
            $this->categoryRepository::getNewId(),
            $json_category['name']
        );

        return $this->categoryRepository->insert($newCategory);
    }

    public function getAll()
    {
        return $this->categoryRepository->select();
    }

    /**
     * @param int $id
     * @return \app\modules\api\models\CategoryRecord|null
     */
    public function getById($id)
    {
        return $this->categoryRepository->selectById($id);
    }

    /**
     * @param $id
     * @return false|int|null
     */
    public function deleteById($id)
    {
        try {
            $category = self::getById($id);
            if (is_null($category))
                return null;
            return $category->delete();
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * @param array $json_category
     * @return array|string
     */
    public function update($json_category)
    {
        $itemRecord = self::getById($json_category['id']);

        $updatedCategory = CategoryEntity::withProps(
            $itemRecord->id,
            $itemRecord->name
        );
        $updatedCategory->setName($json_category['name']);

        return $this->categoryRepository->update($updatedCategory);
    }
}
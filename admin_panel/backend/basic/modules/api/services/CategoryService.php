<?php

namespace app\modules\api\services;

use app\modules\api\dto\CategoryDto;
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
     * @param CategoryDto $dto
     */
    public function create($dto)
    {
        $newCategory = CategoryEntity::withProps(
            $this->categoryRepository::getNewId(),
            $dto['name']
        );

        $this->categoryRepository->insert($newCategory);
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
     */
    public function deleteById($id)
    {
        try {
            $category = self::getById($id);
            if (is_null($category))
                return;
            $category->delete();
        } catch (\Throwable $e) {
        }
    }

    /**
     * @param CategoryDto $dto
     */
    public function update($dto)
    {
        $categoryRecord = self::getById($dto->id);

        $updatedCategory = CategoryEntity::withProps(
            $categoryRecord->id,
            $categoryRecord->name
        );
        $updatedCategory->setName($dto->name);

        $this->categoryRepository->update($updatedCategory);
    }
}
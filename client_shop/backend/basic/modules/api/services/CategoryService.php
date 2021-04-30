<?php

namespace app\modules\api\services;

use app\modules\api\repositories\CategoryRepository;

class CategoryService implements ClientServiceInterface
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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
}
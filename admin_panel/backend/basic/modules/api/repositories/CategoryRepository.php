<?php

namespace app\modules\api\repositories;

use app\modules\api\models\CategoryEntity;
use app\modules\api\models\CategoryRecord;

class CategoryRepository implements RepositoryInterface
{
    /**
     * @param CategoryEntity $object
     * @return array|string
     */
    public function insert($object): void
    {
        try {
            $category = new CategoryRecord();
            $category->id = $object->getId();
            $category->name = $object->getName();
            $category->save();
        } catch (\Throwable $e) {
        }
    }


    public function update($object): void
    {
        try {
            $category = CategoryRecord::findOne(['id' => $object->getId()]);
            $category->name = $object->getName();
            $category->save();
        } catch (\Throwable $e) {
        }
    }

    /**
     * @param int $id
     * @return CategoryRecord|null
     */
    public function selectById($id)
    {
        $category = CategoryRecord::findOne(['id' => $id]);
        return $category;
    }

    public function select()
    {
        return CategoryRecord::find()->all();
    }

    public static function getNewId(): int
    {
        return CategoryRecord::maxId() + 1;
    }

    /**
     * @param CategoryRecord $object
     */
    public function delete($object): void
    {
        $object->delete();
    }
}
<?php


namespace app\modules\api\repositories;


use app\modules\api\models\CategoryEntity;
use app\modules\api\models\CategoryRecord;

class CategoryRepository implements RepositoryInterface
{
    private $categoryRecord;

    public function __construct(CategoryRecord $categoryRecord)
    {
        $this->categoryRecord = $categoryRecord;
    }

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

            if ($category->save()) {
//                return $object->getId();
            }
//            var_damp( $category->errors );
        } catch (\Throwable $e) {
        }
    }


    public function update($object): void
    {
        try {
            $category = $this->categoryRecord::findOne(['id' => $object->getId()]);
            $category->name = $object->getName();

            if ($category->save()) {
//                return $object->getId();
            }
//            var_damp( $category->errors );
        } catch (\Throwable $e) {
        }
    }

    /**
     * @param int $id
     * @return CategoryRecord|null
     */
    public function selectById($id)
    {
        $category = $this->categoryRecord::findOne(['id' => $id]);
        return $category;
    }

    public function select()
    {
        return $this->categoryRecord::find()->all();
    }

    public static function getNewId()
    {
        return CategoryRecord::maxId() + 1;
    }
}
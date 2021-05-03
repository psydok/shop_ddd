<?php

namespace app\modules\api\repositories;

use app\modules\api\models\CategoryEntity;
use app\modules\api\models\CategoryRecord;
use function app\modules\api\services\brokers\sendMessageInRabbit;

require_once("require.php");

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
            sendMessageInRabbit(["category" => ["insert" => $category->toArray()]]);

        } catch (\Throwable $e) {
        }
    }


    public function update($object): void
    {
        try {
            $category = CategoryRecord::findOne(['id' => $object->getId()]);
            $category->name = $object->getName();
            $category->save();

            sendMessageInRabbit(["category" => ["update" => $category->toArray()]]);
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

        sendMessageInRabbit(["category" => ["delete" => $object->toArray()]]);
    }
}
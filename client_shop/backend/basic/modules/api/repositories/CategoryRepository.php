<?php

namespace app\modules\api\repositories;

use app\modules\api\models\CategoryRecord;
use app\modules\api\models\ItemRecord;
use Yii;
use yii\mongodb\Query;


class CategoryRepository
{
    /**
     * @param int $id
     * @return CategoryRecord|null
     */
    public function selectById($id)
    {
        $items = ItemRecord::find()->where(['category_id' => $id])->all();
        $itemsByCategory = $this->getStruct($items);
        return $itemsByCategory[0];
    }

    public function select()
    {
        $query = new Query();
        $query->select(['name', 'status'])
            ->from('customer')
            ->limit(10);
// execute the query
        $rows = $query->all();
        var_dump($query);
        $items = ItemRecord::find()->all();
        return $this->getStruct($items);
    }

    private function getStruct($items)
    {
        $itemsByIdCategory = [];
        $itemsByCategories = [];
        foreach ($items as $item) {
            $itemsByIdCategory[$item['category_id']][] = $item;
        }
        foreach (array_keys($itemsByIdCategory) as $key) {
            $category = CategoryRecord::findOne(['id' => $key])->getAttributes();
            $category['items'] = $itemsByIdCategory[$key];
            $itemsByCategories[] = $category;
        }
        return $itemsByCategories;
    }
}
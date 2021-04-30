<?php

namespace app\modules\api\models;

class ItemRecord extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'item';
    }

    public function rules()
    {
        return [
            [['name', 'category_id', 'price'], 'required'],
            [['id', 'category_id'], 'integer'],
            ['price', 'double', 'min' => 0],
            ['name', 'string', 'max' => 255],
            ['img_link', 'string', 'max' => 5000],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'category_id' => function () {
                return CategoryRecord::findOne(['id' => $this->category_id]);
            },
            'price',
            'img_link'
        ];
    }

    function getDto() : ItemDto
    {
        $dto = new ItemDto();
        $dto->id = $this->id;
        $dto->name = $this->name;
        $dto->price = $this->price;
        $dto->img_link = $this->img_link;
        $dto->category_id = $this->category_id;
        return $dto;
    }
}
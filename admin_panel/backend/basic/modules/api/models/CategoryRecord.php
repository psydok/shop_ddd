<?php


namespace app\modules\api\models;

use app\modules\api\dto\CategoryDto;

class CategoryRecord extends BaseActionRecord
{
    public static function tableName()
    {
        return 'category';
    }

    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            ['id', 'integer'],
            ['name', 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        return $fields;
    }

    function getDto() : CategoryDto
    {
        $dto = new CategoryDto();
        $dto->id = $this->id;
        $dto->name = $this->name;
        return $dto;
    }
}
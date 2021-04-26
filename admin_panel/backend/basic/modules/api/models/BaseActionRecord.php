<?php


namespace app\modules\api\models;


abstract class BaseActionRecord extends \yii\db\ActiveRecord
{
    public static function maxId()
    {
        return self::find()->max('id');
    }

    abstract function getDto();
}
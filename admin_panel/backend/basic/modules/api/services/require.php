<?php
namespace app\modules\api\services\brokers;

function getStruct($items)
{
    $itemsByIdCategory = [];
    $itemsByCategories = [];
    foreach ($items as $item) {
        $itemsByIdCategory[$item['category_id']][] = $item;
        var_dump($item['category_id']);
    }
    foreach (array_keys($itemsByIdCategory) as $key) {
        //$category = CategoryRecord::findOne(['id' => $key])->getAttributes();
        $category['items'] = $itemsByIdCategory[$key];
        $itemsByCategories[] = $category;
    }
    return $itemsByCategories;
}

function sendMessageInRabbit($message){


    $json = (string)json_encode($message);

    include('SenderMessages.php');
    $sender = new \app\modules\api\services\brokers\SenderMessages();
    $sender->execute($json);
}
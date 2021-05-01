<?php
namespace app\modules\api\services\brokers;

function sendMessageInRabbit($message){
    $json = (string)json_encode($message);
    include('SenderMessages.php');
    $sender = new SenderMessages();
    $sender->execute($json);
}
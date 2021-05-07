<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/CUDReceiver.php';
use brokers\CUDReceiver;

$receiver = new CUDReceiver();
$receiver->listen();

<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Receiver.php';
use brokers\Receiver;

$receiver = new Receiver();
$receiver->listen();

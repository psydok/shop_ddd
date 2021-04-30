<?php

use brokers\Receiver;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Receiver.php';

$receiver = new Receiver();
$receiver->listen();

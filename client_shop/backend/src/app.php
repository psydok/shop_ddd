<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\DefaultController;
use Comet\Comet;

$app = new Comet([
    'host' => '0.0.0.0',
    'port' => getenv('CLIENT_PORT_EXT')
]);

$app->setBasePath("/api");

$app->get('/',
    'app\controllers\DefaultController:getCounter');

$app->get('/categories',
    'app\controllers\DefaultController:getCatalog');

$app->get('/categories/{id}',
    'app\controllers\DefaultController:getCatalog');

$app->run();
?>
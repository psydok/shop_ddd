<?php
declare(strict_types=1);

use app\controllers\DefaultController;

require_once __DIR__ . '/../vendor/autoload.php';

use Comet\Comet;

$app = new Comet([
    'host' => '0.0.0.0',
    'port' => getenv('CLIENT_PORT_EXT'),
]);

//$app = new Comet([
//    'host' => '127.0.0.1',
//    'port' => 8090,
//]);

$app->setBasePath("/api");

$app->get('/',
    'app\controllers\DefaultController:getCounter');

$app->get('/categories',
    'app\controllers\DefaultController:getCatalog');

$app->get('/categories/{id}',
    'app\controllers\DefaultController:getCatalog');

$app->get('/hello',
    function ($request, $response) {
        return $response
            ->with("Hello, User!");
    });

$app->run();
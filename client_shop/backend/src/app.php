<?php
declare(strict_types=1);

use app\client\controllers\DefaultController;

require_once __DIR__ . '/../vendor/autoload.php';

use Comet\Comet;


$app = new Comet([
    'host' => '0.0.0.0',
    'port' => getenv('CLIENT_PORT_EXT'),
]);

$app->setBasePath("/api/v1");

$app->get('/categories',
    'app\client\controllers\DefaultController:getCounter');

$app->get('/categories/{name}',
    'app\client\controllers\DefaultController:getCounter');

$app->get('/hello',
    function ($request, $response) {
        return $response
            ->with("Hello, Comet!");
    });

$app->run();
<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\DefaultController;
use Comet\Comet;

$app = new Comet([
    'host' => '0.0.0.0',
    'port' => getenv('PORT')
]);

$app->addBodyParsingMiddleware();
$app->add(\app\middleware\CorsMiddleware::class);
$app->addRoutingMiddleware();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

//---------------------------user---------------------------//

$app->post('/auth',
    'app\controllers\DefaultController:login');
$app->post('/register',
    'app\controllers\DefaultController:register');

//---------------------------services------------------------//
$app->any('/{routes:.+}',
    'app\controllers\DefaultController:getGatewayResp');

//-----------------------------------------------------------//
$app->run();


?>

<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\DefaultController;
use app\models\UserEntity;
use app\repositories\UsersRepository;
use Comet\Comet;
use Comet\Request;
use Comet\Response;

$app = new Comet([
    'host' => '0.0.0.0',
    'port' => getenv('PORT')
]);

$app->addBodyParsingMiddleware();
$app->add(\app\middleware\CorsMiddleware::class);
$app->addRoutingMiddleware();

$app->options('/{routes:.+}', function ($request, $response, $args) {
    $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', '*');
    return $response;
});

//---------------------------user---------------------------//

$app->post('/auth',
    'app\controllers\DefaultController:getAuth');
$app->post('/register',
    'app\controllers\DefaultController:createUser');

//---------------------------admin---------------------------//
$app->get('/admin',
    'app\controllers\DefaultController:getGatewayResp');
$app->get('/admin/{path}',
    'app\controllers\DefaultController:getGatewayResp');
$app->get('/admin/{path}/{id}',
    'app\controllers\DefaultController:getGatewayResp');
$app->post('/admin/{path}',
    'app\controllers\DefaultController:getGatewayResp');
$app->put('/admin/{path}/{id}',
    'app\controllers\DefaultController:getGatewayResp');
$app->delete('/admin/{path}/{id}',
    'app\controllers\DefaultController:getGatewayResp');

//---------------------------shop---------------------------//
$app->get('/catalog',
    'app\controllers\DefaultController:getGatewayResp');
$app->get('/catalog/{path}',
    'app\controllers\DefaultController:getGatewayResp');
$app->get('/catalog/{path}/{id}',
    'app\controllers\DefaultController:getGatewayResp');

//---------------------------start---------------------------//
$app->run();


?>

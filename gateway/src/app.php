<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\DefaultController;
use Comet\Comet;

$app = new Comet([
    'host' => '0.0.0.0',
    'port' => getenv('PORT'),
    'debug' => true
]);

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

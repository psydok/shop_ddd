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
$app->setBasePath("/api");
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path" => "/admin",
    "ignore" => ["/catalog"],
    "secret" => getenv("JWT_SECRET"),
    "secure" => false,
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "0";
        $data["message"] = $arguments["message"];
        $data["data"] = "";
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));

//---------------------------user---------------------------//
$app->get('/auth',
    'app\controllers\DefaultController:getAuth');
$app->post('/register',
    'app\controllers\DefaultController:createUser');

//---------------------------admin---------------------------//
//$app->get('/admin',
//    'app\controllers\DefaultController:getGateway');
//$app->get('/admin/{path}',
//    'app\controllers\DefaultController:getGateway');
//$app->post('/admin/{path}',
//    'app\controllers\DefaultController:getGateway');
//$app->put('/admin/{path}',
//    'app\controllers\DefaultController:getGateway');
//$app->delete('/admin/{path}',
//    'app\controllers\DefaultController:getGateway');
//
////---------------------------shop---------------------------//
//$app->get('/catalog',
//    'app\controllers\DefaultController:getGateway');
//$app->get('/catalog/{path}',
//    'app\controllers\DefaultController:getGateway');

//---------------------------start---------------------------//
$app->run();
?>

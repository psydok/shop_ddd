<?php
declare(strict_types=1);

namespace app\client\controllers;

use Comet\Request;
use Comet\Response;

class DefaultController
{
    private static $counter = 0;

    public function getCounter(Request $request, Response $response, $args)
    {
        $response->getBody()->write(self::$counter);
        return $response->withStatus(200);
    }

    public function setCounter(Request $request, Response $response, $args)
    {
        $name = $args['name'];

        $body = (string) $request->getBody();
        $json = json_decode($body);
        if (!$json) {
            return $response->withStatus(500);
        }
        self::$counter = $json->counter;
        return $response;
    }
}
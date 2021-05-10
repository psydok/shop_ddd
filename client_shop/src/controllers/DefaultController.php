<?php
declare(strict_types=1);

namespace app\controllers;

use app\services\ClientCatalogService;
use Comet\Request;
use Comet\Response;

require_once __DIR__ . '/../../vendor/autoload.php';

class DefaultController
{
    private static $counter = 0;

    public function getCounter(Request $request, Response $response, $args)
    {
        $response->getBody()->write('Count of requests: ' . self::$counter);
        return $response->withStatus(200);
    }

    public function getCatalog(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-Type', 'application/json');
        $service = new ClientCatalogService();
        try {
            self::$counter += 1;
            if (!empty($args)) {
                $categoryId = $args['id'];
                $catalog = $service->getItemsByIdCategory((int)$categoryId);
            } else $catalog = $service->getCatalogsCategories();

            $newResponse->getBody()->write(json_encode($catalog));
            return $newResponse->withStatus(200);
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
        return $newResponse->withStatus(418);
    }
}
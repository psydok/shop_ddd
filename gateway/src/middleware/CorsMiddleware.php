<?php

namespace app\middleware;


use Psr\Http\Server\MiddlewareInterface;
use Slim\Routing\RouteContext;

class CorsMiddleware implements MiddlewareInterface
{

    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $routingResults = $routeContext->getRoutingResults();
        $methods = $routingResults->getAllowedMethods();
        $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

        $response = $handler->handle($request);

        $response = $response
            ->withHeader('Origin', ['*'])
            ->withHeader('Access-Control-Allow-Origin', ['*'])
            ->withHeader('Access-Control-Request-Method', ['*'])
            ->withHeader('Access-Control-Request-Headers', ['*'])
            ->withHeader('Access-Control-Allow-Headers', ['Content-Type'])
            ->withHeader('Access-Control-Expose-Headers', ['*'])
            ->withHeader('Access-Control-Allow-Methods', ['POST, PUT, OPTIONS, GET, DELETE']);

        // Optional: Allow Ajax CORS requests with Authorization header
//        $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
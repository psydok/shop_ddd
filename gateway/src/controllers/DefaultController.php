<?php
declare(strict_types=1);

namespace app\controllers;

use app\models\UserEntity;
use app\repositories\UsersRepository;
use Comet\Request;
use Comet\Response;
use Firebase\JWT\JWT;

require_once __DIR__ . '/../../vendor/autoload.php';

class DefaultController
{
    private function getUser($data, Request $request)
    {
        var_dump($data);
        $user = UserEntity::withParams(
            $data['login'],
            $data['password']
        );
        return $user;
    }

    private function getJWT(UserEntity $user)
    {
        $key = getenv('JWT_TOKEN');
        $payload = array(
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "data" => array(
                "id" => $user->getId(),
                "login" => $user->getLogin(),
                "role" => $user->getRole()
            )
        );
        return JWT::encode($payload, $key);
    }

    public function createUser(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-Type', 'application/json');
        try {
            $newUser = $this->getUser($request->getParsedBody(), $request);
            $repository = new UsersRepository();
            $repository->insertUser($newUser);
            return $newResponse->withStatus(201);
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
        return $newResponse->withStatus(418);
    }

    public function getAuth(Request $request, Response $response, $args)
    {
        $newResponse = $response->withHeader('Content-Type', 'application/json');
        try {
            $user = $this->getUser($request->getQueryParams(), $request);
            $repository = new UsersRepository();
            if ($id = $repository->compareUser($user)) {
                $user->setId((int)$id);
                $jwt = $this->getJWT($user);
                $body = json_encode([
                    "message" => "Успешный вход в систему.",
                    "jwt" => $jwt
                ]);
                $newResponse->getBody()->write($body);
                $newResponse->withHeader('Authorization', $jwt);
                return $newResponse->withStatus(200);
            }
            $newResponse->getBody()->write(json_encode([
                "message" => "Ошибка авторизации."
            ]));
            return $newResponse->withStatus(200);

        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
        return $newResponse->withStatus(418);
    }

    public function getGateway(Request $request, Response $response, $args)
    {
        $token = $request->getAttribute("token");

        var_dump($request->getHeader("Authorization"));
        var_dump($_SERVER["HTTP_AUTHORIZATION"]);
        var_dump($request->getUri());
        $newResponse = $response->withHeader('Content-Type', 'application/json');
        try {
            if (!empty($args)) {
                $token = $args['token'];
            }
            return $newResponse->withStatus(200);
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $e->getTraceAsString();
        }
        return $newResponse->withStatus(418);
    }
}
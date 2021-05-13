<?php
declare(strict_types=1);

namespace app\controllers;

use app\models\UserEntity;
use app\repositories\UsersRepository;
use Comet\Request;
use Comet\Response;
use Firebase\JWT\JWT;
use UnexpectedValueException;

require_once __DIR__ . '/../../vendor/autoload.php';

class DefaultController
{
    private static array $UNAUTHORIZED = [
        "name" => "Unauthorized",
        "message" => "Your request was made with invalid credentials.",
        "status" => 401
    ];
    private static string $SHOP = 'http://shop:';
    private static string $ADMIN = 'http://admin_panel:';

    public function createUser(Request $request, Response $response, $args)
    {
        $newResponse = $response
            ->withHeader('Content-Type', 'application/json');
        try {
            $data = $request->getParsedBody();
            $newUser = UserEntity::withParams(
                $data['login'],
                $data['password']
            );
            $repository = new UsersRepository();
            $repository->insertUser($newUser);
            $newResponse->getBody()->write(json_encode([
                "message" => "success",
                "code" => 201
            ]));
            return $newResponse->withStatus(201);
        } catch (\Exception $e) {
            echo $e->getMessage();
            $newResponse->getBody()->write(json_encode([
                "name" => "fail",
                "message" => $e->getMessage(),
                "code" => 200
            ]));
        }
        return $newResponse->withStatus(200);
    }

    public function getAuth(Request $request, Response $response, $args)
    {
        $newResponse = $response
            ->withHeader('Content-Type', 'application/json');
        try {
            $data = $request->getParsedBody();
            $user = UserEntity::withCleanParams(
                $data['login'],
                $data['password']
            );
            $repository = new UsersRepository();
            if ($id = $repository->compareUser($user)) {
                $jwt = $this->getJWT($repository->getByLogin($user->getLogin()));
                $body = json_encode([
                    "message" => "Успешный вход в систему.",
                    "jwt" => $jwt,
                    "code" => 200
                ]);
                $cookie = 'token=' . urlencode($jwt) . '; path=/; secure; httponly';
                $newResponse->withAddedHeader('Set-Cookie', $cookie);
                $newResponse->getBody()->write($body);

                return $newResponse->withStatus(200);
            }
            $newResponse->getBody()->write(json_encode([
                "message" => "Ошибка авторизации.",
                "code" => 401
            ]));
            return $newResponse->withStatus(401);

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return $newResponse->withStatus(400);
    }

    public function getGatewayResp(Request $request, Response $response, $args)
    {
        $path = $request->getUri()->getPath();
        if (array_intersect(explode('/', $path), ['admin'])) {
            $requestServer = self::$ADMIN . getenv('PORT_INT');
        } else {
            $requestServer = self::$SHOP . getenv('PORT_INT');
            return $this->sendOnServer($requestServer, $request, $response);
        }

        $auth = $request->getHeader("Authorization");
        if ($auth)
            $token = explode(' ', $auth[0])[1];
        else $token = "";

        try {
            $jwt = JWT::decode($token, getenv('JWT_SECRET'), array('HS256'));
            try {
                if ($this->isPermission($path, $jwt)) {
                    return $this->sendOnServer($requestServer, $request, $response);
                }
            } catch (\Exception $exception) {
                var_dump($exception->getMessage());
            }
        } catch (UnexpectedValueException $e) {
            var_dump($e->getMessage());
        }
        $newResp = $response->withHeader('Content-Type', 'application/json');
        $newResp->getBody()->write(
            json_encode(self::$UNAUTHORIZED)
        );
        return $newResp->withStatus(401);
    }

    /**
     * Создание JWT
     * @param UserEntity $user
     * @return string
     */
    private function getJWT(UserEntity $user)
    {
        $key = getenv('JWT_SECRET');
        $payload = array(
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "data" => array(
                "id" => $user->getId(),
                "login" => $user->getLogin(),
                "role" => $user->getRole()
            )
        );
        return JWT::encode($payload, $key, 'HS256');
    }

    /**
     * Проверка на возможность получить доступ к серверу
     * @param $path
     * @param $jwt
     * @return bool
     */
    private function isPermission($path, $jwt)
    {
        $flag = true;
        if (array_intersect(explode('/', $path), ['admin'])) {
            if (!empty($jwt) and $jwt->data->role == 'admin') {
                return $flag;
            }
            $flag = false;
            return $flag;
        }
        return $flag;
    }

    /**
     * Формирование запроса на сервер
     * @param $server
     * @param $path
     * @param $method
     * @param $body
     * @param $header
     * @return bool|string
     */
    private function sendRequest($server, $path, $method, $body, $header)
    {
        $newHeader = array(
            "Content-Type: application/json"
        );
        if (array_key_exists('authorization', $header)) {
            $authorization = $header['authorization'];
            $auth = 'Authorization: ' . str_replace('"', '', json_encode($authorization[0]));
            $newHeader[] = $auth;
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $server . $path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => $newHeader,
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        var_dump($response . "\n");
        return $response;
    }

    /**
     * Отправка запроса на сервер и получение ответа
     * @param $requestServer
     * @param $request
     * @param $response
     * @return mixed
     */
    private function sendOnServer($requestServer, $request, $response)
    {
        $response1 = $this->sendRequest(
            $requestServer,
            $request->getUri()->getPath(),
            $request->getMethod(),
            $request->getParsedBody(),
            $request->getHeaders()
        );
        $newResp = $response->withHeader('Content-Type', 'application/json');
        $newResp->getBody()->write($response1);
        return $newResp;
    }

//    public function getGatewayTest(Request $request, Response $response, $args)
//    {
//        $SHOP = 'http://shop:' . getenv('PORT_INT');
//        $ADMIN = 'http://admin_panel:' . getenv('PORT_INT');
//        $auth = $request->getHeader("Authorization");
//
//        if ($auth)
//            $token = explode(' ', $auth[0])[1];
//        else $token = "";
//        try {
//            $jwt = JWT::decode($token, getenv('JWT_SECRET'), array('HS256')) ?? "";
//            $response =
//                $this->checkPermissionTest($request->getUri()->getPath(), $jwt, $response->withHeader('Content-Type', 'application/json'));
//            return $response;
//        } catch (\Throwable $e) {
//            var_dump($e->getMessage());
//            $newResp = $response->withHeader('Content-Type', 'application/json');
//            $newResp->getBody()->write(
//                json_encode(self::$UNAUTHORIZED)
//            );
//            return $newResp->withStatus(401);
//        }
//    }

//    private function checkPermissionTest($path, $jwt, $response)
//    {
//        $response->getBody()->write(json_encode(["message" => "Доступ разрешен"]));
//        $response->withStatus(200);
//
//        if (array_intersect(explode('/', $path), ['admin'])) {
//            if (!empty($jwt) and $jwt->data->role == 'admin') {
//                return $response;
//            }
//            $response->getBody()->write(
//                json_encode(self::$UNAUTHORIZED)
//            );
//            $response->withStatus(401);
//            return $response;
//        }
//        return $response;
//    }
}
<?php

namespace app\modules\api\controllers;

use app\modules\api\models\ItemRecord;
use app\modules\api\services\CategoryService;
use app\modules\api\services\ItemService;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    private $itemService;
    private $categoryService;

    public function __construct($id, $module, ItemService $itemService, CategoryService $categoryService, $config = [])
    {
        $this->itemService = $itemService;
        $this->categoryService = $categoryService;
        parent::__construct($id, $module, $config);
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    private function checkNameTable($table)
    {
        return substr($table, 0, 7) === 'categor';
    }

    private static function returnBadRequest()
    {
        Yii::$app->response->setStatusCode(400);
        return 'Bad Request';
    }

    public function actionIndex($objects)
    {
        if ($this->checkNameTable($objects))
            $data = $this->categoryService->getAll();
        else $data = $this->itemService->getAll();
        return $this->asJson($data);
    }

    public function actionView($objects, $id)
    {
        if (!empty($id)) {

            if ($this->checkNameTable($objects))
                $result = $this->categoryService->getById($id);
            else $result = $this->itemService->getById($id);
            if (empty($result))
                Yii::$app->response->statusCode = 404;
            return $this->asJson($result);
        }
        Yii::$app->response->setStatusCode(404);
        return 'Not Found';
    }

    /**
     * Создаем object
     * @return array|string
     */
    public function actionCreate($objects)
    {
        $request = Yii::$app->request;
        $rawBody = $request->getRawBody();
        var_dump($request->post());
//                if ($form->load(\Yii::$app->request->post()) && $form->validate()) { $form->getDto()
//       https://elisdn.ru/blog/105/services-and-controllers
        if ($request->isPost && !empty($rawBody)) {

            $body_json = json_decode($rawBody, true);

            if ($this->checkNameTable($objects)){
                $result = $this->categoryService->create($body_json);
            }
            else {
                $form = new ItemRecord();
                $result = $this->itemService->create($body_json);
            }
            if (!$result) {
                Yii::$app->response->setStatusCode(400); // 418
                return $result;
            }
            Yii::$app->response->setStatusCode(201);
            return $result;
        }

        return self::returnBadRequest();
    }

    public function actionDelete($objects, $id)
    {
        if (!empty($id)) {
            if ($this->checkNameTable($objects))
                $data = $this->categoryService->deleteById($id);
            else $data = $this->itemService->deleteById($id);
            if (empty($data)){
                Yii::$app->response->statusCode = 404;
                return $objects . ' by ID ' . $id . ' not found';

            }
            return $objects . ' by ID ' . $id . ' deleted! ';
        }

        return self::returnBadRequest();
    }

    public function actionUpdate($objects, $id)
    {
        if (empty($id)) {
            return self::returnBadRequest();
        }

        $request = Yii::$app->request;
        $rawBody = $request->getRawBody();
        if ($request->isPut && !empty($rawBody)) {
            $body_json = json_decode($rawBody, true);
            $body_json['id'] = $id;
            if ($this->checkNameTable($objects))
                $result = $this->categoryService->update($body_json);
            else $result = $this->itemService->update($body_json);

            if (!$result) {
                Yii::$app->response->setStatusCode(418); // 418
                return $result;
            }
            Yii::$app->response->setStatusCode(202);
            return $result;
        }

        return self::returnBadRequest();
    }
}

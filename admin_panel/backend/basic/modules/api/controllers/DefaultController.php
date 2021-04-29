<?php

namespace app\modules\api\controllers;

use app\modules\api\models\CategoryRecord;
use app\modules\api\models\ItemRecord;
use app\modules\api\services\CategoryService;
use app\modules\api\services\ItemService;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `api` module
 */
class DefaultController extends \yii\rest\Controller
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

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::class,
            'cors' => [
                // restrict access to
                'Origin' => ['*'],
                // Allow  methods
                'Access-Control-Request-Method' => ['POST', 'PUT', 'OPTIONS', 'GET', 'DELETE'],
                'Access-Control-Allow-Origin' => '*',
                // Allow only headers 'X-Wsse'
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Headers' => ['Content-Type'],
                // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                // 'Access-Control-Allow-Credentials' => true,
                // Allow OPTIONS caching
                'Access-Control-Max-Age' => 3600,
                // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                'Access-Control-Expose-Headers' => ['*']
            ],
        ];
        return $behaviors;
    }

    private function checkTableCategory($table)
    {
        return substr($table, 0, 7) === 'categor';
    }

    private static function returnBadRequest()
    {
        Yii::$app->response->format = 'json';
        Yii::$app->response->setStatusCode(400);
        $data['message'] = 'bad request';
        return $data;
    }

    private static function returnSuccess()
    {
        Yii::$app->response->format = 'json';
        $data['message'] = "success";
        return $data;
    }

    public function actionIndex($objects)
    {
        if ($this->checkTableCategory($objects))
            $data = $this->categoryService->getAll();
        else $data = $this->itemService->getAll();
        return $this->asJson($data);
    }

    public function actionView($objects, $id)
    {
        if (!empty($id)) {
            if ($this->checkTableCategory($objects))
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

        if ($request->isPost && !empty($rawBody)) {

            if ($this->checkTableCategory($objects)) {
                $form = new CategoryRecord();
                $service = $this->categoryService;
            } else {
                $form = new ItemRecord();
                $service = $this->itemService;
            }

            $body_json = json_decode($rawBody, true);
            if ($form->load($body_json, '') && $form->validate()) {
                $service->create($form->getDto());

                Yii::$app->response->setStatusCode(201);
                return self::returnSuccess();
            }
        }
        return self::returnBadRequest();
    }

    public function actionDelete($objects, $id)
    {
        if (!empty($id)) {
            if (Yii::$app->request->isDelete) {
                if ($this->checkTableCategory($objects)) {
                    $service = $this->categoryService;
                } else {
                    $service = $this->itemService;
                }

                $service->deleteById($id);

                Yii::$app->response->setStatusCode(200);
                return self::returnSuccess();
            }
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
            if ($this->checkTableCategory($objects)) {
                $form = new CategoryRecord();
                $service = $this->categoryService;
            } else {
                $form = new ItemRecord();
                $service = $this->itemService;
            }
            $body_json = json_decode($rawBody, true);
            $body_json['id'] = $id;
            if ($form->load($body_json, '') && $form->validate()) {
                $service->update($form->getDto());

                Yii::$app->response->setStatusCode(202);
                return self::returnSuccess();
            }
        }

        return self::returnBadRequest();
    }
}

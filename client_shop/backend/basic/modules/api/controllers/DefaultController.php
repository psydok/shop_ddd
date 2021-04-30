<?php

namespace app\modules\api\controllers;

use app\modules\api\services\CategoryService;
use Yii;

/**
 * Default controller for the `api` module
 */
class DefaultController extends \yii\rest\Controller
{
    private $categoryService;

    public function __construct($id, $module, CategoryService $categoryService, $config = [])
    {
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
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['OPTIONS', 'GET'],
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Headers' => ['Content-Type'],
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['*']
            ],
        ];
        return $behaviors;
    }

    public function actionOptions()
    {
        Yii::$app->response->format = 'json';
        Yii::$app->response->setStatusCode(200);
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

    public function actionIndex()
    {
        Yii::$app->response->setStatusCode(418);
        $data = $this->categoryService->getAll();
        return $this->asJson($data);
    }

    public function actionView($id)
    {
        if (!empty($id)) {

            $result = $this->categoryService->getById($id);

            if (empty($result))
                Yii::$app->response->statusCode = 404;

            return $this->asJson($result);
        }
        Yii::$app->response->setStatusCode(404);
        return 'Not Found';
    }
}

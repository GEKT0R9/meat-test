<?php

namespace app\controllers;

use app\repository\ProductsRepository;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;

class ProductsController extends \yii\rest\Controller
{
    public function behaviors()
    {
        return [
            'authenticator' => [
                'class' => HttpBearerAuth::class,
                'except' => ['options']
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => ['list'],
                'rules' => [
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    public function actionList()
    {
        return ProductsRepository::getProductsToList();
    }
}
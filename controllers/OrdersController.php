<?php

namespace app\controllers;

use app\models\OrderForm;
use app\operations\OrdersOperations;
use app\repository\OrdersRepository;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;

class OrdersController extends \yii\rest\Controller
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
                'only' => ['list', 'add'],
                'rules' => [
                    [
                        'actions' => ['list', 'add'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    public function actionList($user_id){
        return OrdersRepository::getOrdersToList(['user_id' => $user_id]);
    }

    public function actionAdd(){
        $model = new OrderForm();
        $model->load(\Yii::$app->request->post(), '');
        if ($model->validate()){
            $order_id = OrdersOperations::createOrder(
                $model->user_id,
                $model->products,
                $model->comment
            );
            if ($order_id){
                $order = OrdersRepository::getOrder(['id' => $order_id]);
                return [
                    'order_id' => $order->id,
                    'status' => $order->status->title
                ];
            }
            return [
                'order_id' => $order_id,
                'status' => 'Возникла ошибка при создании заказа'
            ];
        } else {
            return $model;
        }
    }
}
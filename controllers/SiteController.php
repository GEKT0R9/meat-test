<?php

namespace app\controllers;

use app\models\RefreshForm;
use app\models\RegistrationForm;
use app\repository\ProductsRepository;
use app\repository\UserRepository;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'login' => ['post'],
                    'login-by-token' => ['post'],
                    'registration' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Авторизация по номеру и паролю
     * @path POST /login
     */
    public function actionLogin()
    {
        return $this->login(new LoginForm());
    }

    /**
     * Авторизация по токену
     * @path POST /login
     */
    public function actionLoginByToken()
    {
        return $this->login(new RefreshForm());
    }

    /**
     * Метод авторизации
     */
    private function login($model): array
    {
        $model->load(Yii::$app->request->post(), '');
        if ($token = $model->login()) {
            $user = Yii::$app->user->identity;
            return [
                'token' => $token,
                'user' => [
                    'user_id' => $user->getId(),
                    'phone' => $user->phone,
                    'name' => $user->name,
                    'address' => $user->address,
                ]
            ];
        } else {
            return $model;
        }
    }


    /**
     * Регистрация пользователя
     * @path POST /registration
     */
    public function actionRegistration()
    {
        $model = new RegistrationForm();
        $model->load(\Yii::$app->request->post(), '');
        if ($model->validate()) {
            return UserRepository::createUser(
                $model->phone,
                $model->password,
                $model->username,
                $model->address
            );
        } else {
            return $model;
        }
    }
}

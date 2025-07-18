<?php

namespace app\entity;

use app\repository\UserRepository;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Таблица пользователей
 * @property int id идентификатор
 * @property string phone номер телефона
 * @property string password пароль
 * @property string name имя
 * @property string address адрес
 *
 * @package app\entity
 */
class Users extends ActiveRecord implements IdentityInterface
{
    public static function findIdentity($id)
    {
        return UserRepository::getUser(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return UserRepository::getUserByAccessToken($token);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        $userAgent = Yii::$app->request->getUserAgent();
        $userIp = Yii::$app->request->getUserIP();
        return UserRepository::getAccessToken($this->id, $userAgent, $userIp);
    }

    public function validateAuthKey($authKey)
    {
        return UserRepository::validateAccessToken($authKey);
    }

    public function createToken()
    {
        $token = UserRepository::createToken($this->id);
        return [
            'access' => [
                'token' => $token->access_token,
                'lifetime' => $token->lifetime_access,
            ],
            'refresh' => [
                'token' => $token->refresh_token,
                'lifetime' => $token->lifetime_refresh,
            ],
            'create_time' => strtotime($token->create_time)
        ];
    }

    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    public static function findByPhone($phone)
    {
        return new static(UserRepository::getUser(['phone' => $phone]));
    }
}
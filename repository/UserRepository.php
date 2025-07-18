<?php

namespace app\repository;

use app\entity\Users;
use app\entity\UsersToken;
use Yii;
use yii\db\ActiveRecord;

class UserRepository
{
    /**
     * Создание пользователя
     * @param string $phone номер телефона
     * @param string $password пароль
     * @param string $name имя
     * @param string $address адрес
     * @return Users
     */
    public static function createUser(
        string $phone,
        string $password,
        string $name,
        string $address
    )
    {
        $new_user = new Users();
        $new_user->phone = $phone;
        $new_user->password = password_hash($password, PASSWORD_BCRYPT);
        $new_user->name = $name;
        $new_user->address = $address;
        $new_user->save();
        return $new_user;
    }

    /**
     * Получение пользователя по where
     * @param array $where запрос
     * @return array|ActiveRecord|null
     */
    public static function getUser($where)
    {
        return Users::find()->where($where)->one();
    }

    public static function createToken($user_id, $lifetimeAccess = (3600 * 24 * 1), $lifetimeRefresh = (3600 * 24 * 30))
    {
        $token = new UsersToken();
        $token->user_agent = Yii::$app->request->getUserAgent();
        $token->user_ip = Yii::$app->request->getUserIP();
        $token->user_host = Yii::$app->request->getUserHost();
        $token->access_token = Yii::$app->security->generateRandomString(120);
        $token->refresh_token = Yii::$app->security->generateRandomString(120);
        $token->user_id = $user_id;
        $token->lifetime_access = $lifetimeAccess;
        $token->lifetime_refresh = $lifetimeRefresh;
        $token->save();
        return UsersToken::findOne($token->id);
    }

    public static function getAccessToken($user_id, $user_agent, $user_ip)
    {
        $token = UsersToken::find()
            ->where([
                'user_id' => $user_id,
                'user_agent' => $user_agent,
                'user_ip' => $user_ip,
            ])
            ->andWhere('current_timestamp <= (create_time + make_interval(0,0,0,0,0,0,lifetime_access))')
            ->orderBy('create_time DESC')
            ->one();
        if (!empty($token)) {
            return $token->access_token;
        }
        return null;
    }

    public static function validateAccessToken($accessToken): bool
    {
        $token = UsersToken::find()
            ->where([
                'access_token' => $accessToken,
            ])
            ->andWhere('current_timestamp <= (create_time + make_interval(0,0,0,0,0,0,lifetime_access))')
            ->one();
        return !empty($token);
    }

    public static function getUserByAccessToken($accessToken): ?Users
    {
        $token = UsersToken::find()
            ->where([
                'access_token' => $accessToken,
            ])
            ->andWhere('current_timestamp <= (create_time + make_interval(0,0,0,0,0,0,lifetime_access))')
            ->one();
        if ($token) {
            return $token->user;
        }
        return null;
    }

    public static function getTokenByRefresh($refreshToken)
    {
        return UsersToken::find()
            ->where([
                'refresh_token' => $refreshToken,
            ])
            ->andWhere('current_timestamp <= (create_time + make_interval(0,0,0,0,0,0,lifetime_refresh))')
            ->one();
    }

    public static function changePassword($user_id, $new_password)
    {
        $user = Users::findOne($user_id);
        $user->password = password_hash($new_password, PASSWORD_DEFAULT);
        return $user->save();
    }


}
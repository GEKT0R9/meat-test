<?php

namespace app\entity;

use yii\db\ActiveRecord;

/**
 * Таблица токенов пользователей
 * @property int id идентификатор
 * @property string user_agent провайдер
 * @property string user_ip ip пользователя
 * @property string user_host хост пользователя
 * @property string access_token ключ доступа
 * @property string refresh_token ключ обновления
 * @property int user_id
 * @property int lifetime_access время жизни ключа доступа
 * @property int lifetime_refresh время жизни ключа обновления
 * @property string create_time Дата создания токена
 *
 * @property Users user роли
 * @package app\entity
 */
class UsersToken extends ActiveRecord
{
    public static function tableName()
    {
        return 'users_token';
    }

    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }


}
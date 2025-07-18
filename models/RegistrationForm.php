<?php

namespace app\models;

use app\entity\Users;
use app\repository\UserRepository;

class RegistrationForm extends \yii\base\Model
{
    public $username;
    public $phone;
    public $password;
    public $address;

    public function rules()
    {
        return [
            [['address', 'phone', 'password', 'username'], 'required', 'message' => 'Поле не должно быть пустым'],
            ['phone', 'match', 'pattern' => '/^(\+7|8)\d{10}$/', 'message' => 'Телефон некорректного формата'],
            ['password', 'string', 'length' => [8], 'message' => 'Пароль должен быть минимум 8 символов'],
            ['phone', 'validatePhone'],
        ];
    }


    public function validatePhone($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!empty(UserRepository::getUser(['phone' => preg_replace('/^(\+7|8)/', '+7', $this->phone)]))) {
                $this->addError($attribute, 'Пользователь с данным телефоном уже существует');
            }
        }
    }
}
<?php

namespace app\models;

use app\repository\AccessRolesRepository;
use app\repository\UserRepository;
use Yii;
use yii\base\Model;


class NewUserForm extends Model
{
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $patronymic = null;
    public $date_of_birth;
    public $roles = ['user'];


    public function rules()
    {
        return [
            [['first_name', 'last_name', 'date_of_birth', 'email', 'password'], 'required'],
            ['date_of_birth', 'date', 'format' => 'yyyy-MM-dd', 'message' => 'Поле date_of_birth неправильного формата'],
            ['patronymic', 'default', 'value' => ''],
            ['email', 'email', 'message' => 'Поле email неправильного формата'],
            ['email', 'validateEmail'],
            ['roles', 'in', 'range' => AccessRolesRepository::getRoleNames(), 'allowArray' => true]
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = UserRepository::getUser(['email' => $this->email]);

            if (!empty($user)) {
                $this->addError($attribute, 'Email уже занят другим пользователем');
            }
        }
    }
}

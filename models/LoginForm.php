<?php

namespace app\models;

use app\entity\Users;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read Users|null $user
 *
 */
class LoginForm extends Model
{
    public $phone;
    public $password;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['phone','password'], 'required', 'message' => 'Поле не должно быть пустым'],
            ['phone', 'match', 'pattern' => '/^(\+7|8)\d{10}$/', 'message' => 'Номер телефона некорректен'],
            ['password', 'validatePassword'],
        ];
    }

    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if (Yii::$app->user->login($user)) {
                return $user->createToken();
            }
        }
        return false;
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Некорректный данные авторизации.');
            }
        }
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Users::findByPhone(preg_replace('/^(\+7|8)/', '+7', $this->phone));
        }
        return $this->_user;
    }
}

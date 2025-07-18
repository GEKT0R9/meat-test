<?php

namespace app\models;

use app\entity\Users;
use app\repository\UserRepository;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 *
 */
class RefreshForm extends Model
{
    public $token;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['token'], 'required'],
            [['token'], 'validateToken'],
        ];
    }

    public function validateToken($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $userToken = UserRepository::getTokenByRefresh($this->token);
            if (!$userToken) {
                $this->addError($attribute, 'Некорректный токен.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool|array whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $userToken = UserRepository::getTokenByRefresh($this->token)) {
            $user = Users::findIdentity($userToken->user->id);
            $userToken->delete();
            if (Yii::$app->user->login($user)) {
                return $user->createToken();
            }
        }
        return false;
    }
}

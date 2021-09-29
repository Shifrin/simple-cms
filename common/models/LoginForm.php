<?php
namespace common\models;

use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * @var
     */
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // password is validated by validatePassword()
            [['username', 'password'], 'validatePassword'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }

            if ($user && $user->status == User::STATUS_PENDING) {
                $this->addError($attribute, 'Your account is not yet verified.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            return \One::app()->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return false;
    }

    /**
     * After successful login, record time and ip of the logged in user
     */
    public function updateTimeAndIp()
    {
        $user = $this->getUser();
        $user->scenario = User::SCENARIO_SIGNIN;
        $user->last_logged_in_time = time();
        $user->last_logged_in_ip = \One::app()->request->userIP;
        $user->update();
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            if (strpos($this->username, '@') !== false) { // If username is email
                $this->_user = User::findByEmail($this->username, false);
            } else {
                $this->_user = User::findByUsername($this->username, false);
            }
        }

        return $this->_user;
    }
}

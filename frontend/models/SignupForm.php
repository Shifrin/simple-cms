<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use yii\widgets\ActiveForm;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @var bool|User
     */
    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'filter', 'filter' => 'trim'],
            ['username', 'unique', 'targetClass' => '\common\models\User',
                'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 4, 'max' => 16],
            ['email', 'unique', 'targetClass' => '\common\models\User',
                'message' => 'This email address has already been taken.'],
            [
                'password',
                'match',
                'pattern' => '/^(?=^.{8,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[a-z])(?=.*[0-9]))^.*$/',
                'message' => '{attribute} is not strong enough.'
            ],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->scenario = User::SCENARIO_SIGNUP;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->registered_ip = \One::app()->request->userIP;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $user->roles = ['Basic'];

            if ($user->save()) {
                $this->_user = $user;
                return true;
            }
        }

        return false;
    }

    /**
     * Send verification email to registered user
     */
    public function sendVerificationEmail()
    {
        $user = $this->getUser();

        \One::app()->mailer->compose([
            'html' => 'emailVerification-html',
            'text' => 'emailVerification-text',
        ], ['user' => $user])
            ->setFrom([\One::app()->params['adminEmail'] => \One::app()->name . ' No-Replay'])
            ->setTo($this->email)
            ->setSubject('Email Verification ' . \One::app()->name)->send();
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username, false);
        }

        return $this->_user;
    }
}

<?php
namespace common\models;

use common\app\ActiveRecord;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use common\behaviors\UserBehavior;
use yii\behaviors\BlameableBehavior;
use yii\web\IdentityInterface;
use yii\db\Expression;


/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $email_verify_key
 * @property string $registered_ip
 * @property string $last_logged_in_ip
 * @property integer $last_logged_in_time
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 * @property string $roles
 *
 * @property UserProfile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 20;
    const STATUS_PENDING = 30;
    const SCENARIO_SIGNIN = 'signin';
    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_INSTALL = 'install'; // TODO

    public $tmpPassword;
    public $roles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'value' => [$this, 'setUserBehaviourValue']
            ],
            UserBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_SIGNUP] = [
            'username',
            'email',
            'email_verify_key',
            'password_hash',
            'registered_ip',
            'status',
            'auth_key'
        ];
        $scenarios[self::SCENARIO_SIGNIN] = [
            'last_logged_in_time',
            'last_logged_in_ip'
        ];

        return $scenarios;
    }

    /**
     * Set 'NULL' value for the created_by and updated_by attributes on sign up
     * @param $event
     * @return Expression
     */
    public function setUserBehaviourValue($event)
    {
        if ($this->scenario === self::SCENARIO_SIGNUP) {
            return new Expression('NULL');
        } else {
            return \One::app()->user->id;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email'], 'required'],
            ['username', 'match', 'pattern' => '/[^a-z_\-0-9]*$/',
                'message' => '{attribute} should be only alphanumeric characters.',],
            ['username', 'string', 'min' => 4, 'max' => 16],
            [['email'], 'email'],
            [['username', 'email', 'auth_key'], 'unique'],
            [['username', 'email', 'password_hash'], 'filter', 'filter' => 'trim'],
            [['status'], 'default', 'value' => function($model, $attribute) {
                return $model->scenario == User::SCENARIO_SIGNUP ? User::STATUS_PENDING
                    : User::STATUS_ACTIVE;
            }],
            [['status'], 'in', 'range' => array_keys(self::statusList())],
            [['registered_ip', 'last_logged_in_ip'], 'string'],
            [['last_logged_in_time', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'],
                'integer'],
            [['username', 'auth_key', 'email_verify_key', 'password_hash', 'password_reset_token'], 'string'],
            [['roles'], 'in', 'range' => array_keys(\One::app()->authManager->getRoles()), 'allowArray' => true],
        ];
    }

    /**
     * Validates the username.
     * This method serves as the inline validation for username.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateUsername($attribute, $params)
    {
        if (!preg_match('/[^a-z_\-0-9]/', $this->$attribute)) {
            $this->addError($attribute, 'Username accept only alphanumeric characters.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \One::t('app', 'ID'),
            'username' => \One::t('app', 'Username'),
            'auth_key' => \One::t('app', 'Auth Key'),
            'password_hash' => \One::t('app', 'Password Hash'),
            'password_reset_token' => \One::t('app', 'Password Reset Token'),
            'email' => \One::t('app', 'Email'),
            'email_verify_key' => \One::t('app', 'Email Verification Key'),
            'registered_ip' => \One::t('app', 'Registered IP'),
            'last_logged_in_ip' => \One::t('app', 'Last Logged in IP'),
            'last_logged_in_time' => \One::t('app', 'Last Logged in Time'),
            'status' => \One::t('app', 'Status'),
            'created_at' => \One::t('app', 'Created At'),
            'created_by' => \One::t('app', 'Created By'),
            'updated_at' => \One::t('app', 'Updated At'),
            'updated_by' => \One::t('app', 'Updated By'),
            'role' => \One::t('app', 'Role'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->scenario !== self::SCENARIO_SIGNIN) {
            $auth = \One::app()->authManager;

            if (!$insert)
                $auth->revokeAll($this->id);

            foreach ($this->roles as $roleName) {
                $role = $auth->getRole($roleName);

                if ($role !== null) {
                    $auth->assign($role, $this->id);
                }
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     * @param string $username
     * @param bool $statusCheck defaults to true:Active User
     * @return null|static
     */
    public static function findByUsername($username, $statusCheck = true)
    {
        if ($statusCheck) {
            return static::findOne([
                'username' => $username, 'status' => self::STATUS_ACTIVE
            ]);
        } else {
            return static::findOne(['username' => $username]);
        }
    }

    /**
     * Finds user by email
     * @param string $email
     * @param bool $statusCheck defaults to true:Active User
     * @return null|static
     */
    public static function findByEmail($email,  $statusCheck = true)
    {
        if ($statusCheck) {
            return static::findOne([
                'email' => $email, 'status' => self::STATUS_ACTIVE
            ]);
        } else {
            return static::findOne(['email' => $email]);
        }
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = \One::app()->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * Used only for email verification
     * @param string $token
     * @return bool
     */
    public static function isEmailVerificationTokenValid($token)
    {
        $user = static::findOne([
            'email_verify_key' => $token, 'status' => self::STATUS_PENDING
        ]);

        if ($user !== null) {
            $user->status = self::STATUS_ACTIVE;
            return $user->updateAttributes(['status']);
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Get status in words
     * @return string|null
     */
    public function getStatus()
    {
        $list = self::statusList();

        return isset($list[$this->status]) ? $list[$this->status] : null;
    }

    /**
     * Get Profile
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(UserProfile::className(), ['user_id' => 'id']);
    }

    /**
     * Get name
     * If possible, user full name will return from profile
     * @return string
     */
    public function getName()
    {
        if ($this->profile !== null && $this->profile->getName() !== ' ') {
            return $this->profile->getName();
        }

        return ucfirst($this->username);
    }

    /**
     * Get roles that attached to the user
     *
     * @return mixed
     */
    public function getRoles()
    {
        return \One::app()->authManager->getRolesByUser($this->id);
    }

    /**
     * Check the user that is Administrator
     *
     * @return bool
     */
    public function getIsAdmin()
    {
        if (array_key_exists('Administrator', $this->roles)) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \One::app()->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = \One::app()->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = \One::app()->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = \One::app()->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new email verification token
     */
    public function generateEmailVerificationToken()
    {
        $this->email_verify_key = \One::app()->security->generateRandomString();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Helper function
     * Generate a random password
     * @param integer $length defaults to 10
     * @return string generated password
     */
    public static function generateRandomPassword($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, $length);

        return $password;
    }

    /**
     * Helper function
     * This will help to create dropdown for status list
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_DELETED => 'Deleted'
        ];
    }
}

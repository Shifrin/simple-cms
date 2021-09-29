<?php

namespace common\models;

use yii\base\Model;
use \yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * PasswordResetForm is the model behind the password reset form.
 */
class PasswordResetForm extends Model {

    const PROFILE_UPDATE = 'profile_update';

    public $currentPassword;
    public $newPassword;
    /**
     * @var User
     */
    public $user;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            ['currentPassword', 'required', 'on' => self::PROFILE_UPDATE],
            ['currentPassword', 'validateCurrentPassword', 'on' => self::PROFILE_UPDATE],
            ['newPassword', 'required'],
            ['newPassword', 'string', 'min' => 8],
        ];
    }

    /**
     * Validate current password
     * @param $attribute
     * @param $params
     */
    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->user->validatePassword($this->$attribute)) {
            $this->addError($attribute, 'Current password is not valid.');
        }
    }

    /**
     * Update the user password if validations are passed
     * @return bool
     * @throws BadRequestHttpException
     */
    public function resetPassword() {
        if ($this->validate()) {
            $this->user->setPassword($this->newPassword);
            $this->user->generateAuthKey();
            $attributes = ['password_hash', 'auth_key'];

            if ($this->scenario != self::PROFILE_UPDATE) {
                $this->user->removePasswordResetToken();
                $attributes[] = 'password_reset_token';
            }

            return $this->user->updateAttributes($attributes);
        } else {
            \One::app()->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($this);
        }
    }
}
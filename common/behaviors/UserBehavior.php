<?php

namespace common\behaviors;

use common\models\User;
use common\models\UserProfile;
use yii\base\Behavior;

class UserBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            User::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            User::EVENT_AFTER_INSERT => 'afterInsert',
        ];
    }

    /**
     * After validate the User record
     * @param $event
     */
    public function beforeValidate($event)
    {
        if ($this->owner->scenario !== User::SCENARIO_SIGNUP && $this->owner->isNewRecord) {
            $this->owner->password_hash = User::generateRandomPassword();
            $this->owner->tmpPassword = $this->owner->password_hash;
            $this->owner->setPassword($this->owner->password_hash);
            $this->owner->generateAuthKey();
        }
    }

    /**
     * After insert the User record
     * @param $event
     */
    public function afterInsert($event)
    {
        $this->createProfile();
        $this->sendNotification();
    }

    /**
     * Create User Profile
     */
    public function createProfile()
    {
        $profile = new UserProfile();
        $profile->user_id = $this->owner->id;
        $profile->save(false);
    }

    /**
     * Send notification to newly created User
     */
    public function sendNotification()
    {
        if ($this->owner->scenario === User::SCENARIO_SIGNUP) {
            return;
        }

        \One::app()->mailer->compose([
            'html' => 'newUserNotification-html',
            'text' => 'newUserNotification-text',
        ], ['username' => $this->owner->username, 'password' => $this->owner->tmpPassword])
            ->setFrom([\One::app()->params['adminEmail'] => \One::app()->name . ' No-Replay'])
            ->setTo($this->owner->email)
            ->setSubject('New User Account Notification - ' . \One::app()->name)->send();
    }
}
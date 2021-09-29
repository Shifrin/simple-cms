<?php

namespace common\app;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * ActiveRecord Class File
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Get the creator's identity.
     *
     * @return \yii\web\IdentityInterface
     */
    public function getCreatedBy()
    {
        if ($this->hasAttribute('created_by')) {
            return \One::app()->user->identity->findIdentity($this->created_by);
        }

        return null;
    }

    /**
     * Get name of the creator.
     *
     * @return null|string
     */
    public function getCreatorName()
    {
        $creator = $this->getCreatedBy();

        if ($creator) {
            return $creator->getName();
        }

        return null;
    }

    public function getCreatedAt($format = 'short')
    {
        if ($this->hasAttribute('created_at')) {
            return \One::app()->formatter->asDatetime($this->created_at, $format);
        }

        return null;
    }

    /**
     * Get the updater's identity.
     *
     * @return \yii\web\IdentityInterface
     */
    public function getUpdatedBy()
    {
        if ($this->hasAttribute('updated_by')) {
            return \One::app()->user->identity->findIdentity($this->updated_by);
        }

        return null;
    }

    /**
     * Get name of the updater.
     *
     * @return null|string
     */
    public function getUpdaterName()
    {
        $updater = $this->getUpdatedBy();

        if ($updater !== null) {
            return $updater->getName();
        }

        return null;
    }

    public function getUpdatedAt($format = 'short')
    {
        if ($this->hasAttribute('updated_at')) {
            return \One::app()->formatter->asDatetime($this->updated_at, $format);
        }

        return null;
    }

    /**
     * Helper function.
     *
     * @return array
     */
    public static function activeUserList()
    {
        $authors = User::find()->with('profile')->active()->all();

        foreach ($authors as $author) {
            $author->username = $author->getName();
        }

        return ArrayHelper::map($authors, 'id', 'username');
    }
}
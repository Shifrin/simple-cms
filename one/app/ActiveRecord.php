<?php

namespace one\app;

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
}
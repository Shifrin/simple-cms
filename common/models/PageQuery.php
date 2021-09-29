<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Page]].
 *
 * @see Page
 */
class PageQuery extends \yii\db\ActiveQuery
{
    /**
     * Find only published pages.
     * @return $this
     */
    public function publish()
    {
        return $this->andWhere('[[status]]=' . Page::STATUS_PUBLISH);
    }

    /**
     * Find only drafted pages.
     * @return $this
     */
    public function draft()
    {
        return $this->andWhere('[[status]]=' . Page::STATUS_DRAFT);
    }

    /**
     * Find only trashed pages.
     * @return $this
     */
    public function trash()
    {
        return $this->andWhere('[[status]]=' . Page::STATUS_TRASH);
    }

    /**
     * @inheritdoc
     * @return Page[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Page|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

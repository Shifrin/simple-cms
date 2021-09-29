<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Article]].
 *
 * @see News
 */
class ArticleQuery extends \yii\db\ActiveQuery
{
    public $where = ['type' => 'Article'];

    public function trashed()
    {
        return $this->andWhere('[[status]]=10');
    }

    public function draft()
    {
        return $this->andWhere('[[status]]=20');
    }

    public function published()
    {
        return $this->andWhere('[[status]]=30');
    }

    public function pending()
    {
        return $this->andWhere('[[status]]=40');
    }

    /**
     * @inheritdoc
     * @return News[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return News|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

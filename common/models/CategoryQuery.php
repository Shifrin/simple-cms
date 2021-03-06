<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Category]].
 *
 * @see Category
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     *
     * @return Category[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * 
     * @return Category|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

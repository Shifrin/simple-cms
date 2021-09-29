<?php
/**
 * AttachmentQuery Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */


namespace common\models;


use yii\db\ActiveQuery;

class AttachmentQuery extends ActiveQuery
{
    public function thumbnail($size = null)
    {
        return $this->andWhere(['thumbnail_size' => $size]);
    }
}
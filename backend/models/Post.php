<?php

namespace backend\models;

use backend\app\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $summary
 * @property integer $status
 * @property integer $publish_at
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Post extends ActiveRecord
{
    const STATUS_TRASHED = 10;
    const STATUS_DRAFT = 20;
    const STATUS_PENDING = 30;
    const STATUS_REJECTED = 40;
    const STATUS_PUBLISHED = 50;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required', 'message' => 'Please write a {attribute} first.'],
            [['status'], 'required'],
            [['title', 'type', 'slug', 'content', 'summary'], 'string'],
            [['publish_at', 'created_by', 'created_at', 'updated_at', 'updated_by'], 'integer'],
            [
                ['status'],
                'in',
                'range' => array_keys(self::statusList()),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \One::t('app', 'ID'),
            'type' => \One::t('app', 'Type'),
            'title' => \One::t('app', 'Title'),
            'slug' => \One::t('app', 'Slug'),
            'content' => \One::t('app', 'Content'),
            'summary' => \One::t('app', 'Summary'),
            'status' => \One::t('app', 'Status'),
            'publish_at' => \One::t('app', 'Publish At'),
            'created_by' => \One::t('app', 'Created By'),
            'created_at' => \One::t('app', 'Created At'),
            'updated_at' => \One::t('app', 'Updated At'),
            'updated_by' => \One::t('app', 'Updated By'),
        ];
    }

    /**
     * Helper function.
     *
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_TRASHED => 'Trashed',
        ];
    }
}

<?php

namespace common\models;

use One;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%revision}}".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $model_id
 * @property string $model
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $summary
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Revision extends \common\app\ActiveRecord
{
    const TYPE_DEFAULT = 10;
    const TYPE_AUTO_SAVE = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%revision}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'default', 'value' => self::TYPE_DEFAULT],
            [['type', 'model_id', 'model', 'title', 'content'], 'required'],
            [['type', 'model_id'], 'integer'],
            [['model', 'title', 'slug', 'content', 'summary'], 'string'],
            [['summary'], 'default', 'value' => new Expression('NULL')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => One::t('app', 'ID'),
            'type' => One::t('app', 'Type'),
            'model_id' => One::t('app', 'Model ID'),
            'model' => One::t('app', 'Model'),
            'title' => One::t('app', 'Title'),
            'slug' => One::t('app', 'Slug'),
            'content' => One::t('app', 'Content'),
            'summary' => One::t('app', 'Summary'),
            'created_at' => One::t('app', 'Created At'),
            'created_by' => One::t('app', 'Created By'),
            'updated_at' => One::t('app', 'Updated At'),
            'updated_by' => One::t('app', 'Updated By'),
        ];
    }

    /*
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className()
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'immutable' => true,
                'ensureUnique' => true,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        $maxRevision = One::app()->params['maxRevisions'];
        $revisions = self::find()->where([
            'type' => self::TYPE_DEFAULT, 'model_id' => $this->model_id, 'model' => $this->model
        ])->limit($maxRevision)->orderBy(['id' => SORT_ASC])->all();

        if (count($revisions) == $maxRevision) {
            $revisions[0]->delete();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     * @return RevisionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RevisionQuery(get_called_class());
    }
}

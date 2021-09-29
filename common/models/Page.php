<?php

namespace common\models;

use common\app\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use One;

/**
 * Page Class File.
 * This is the model class for table "{{%page}}".
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $main_layout
 * @property string $partial_layout
 * @property integer $status
 * @property integer $publish_at
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Page extends ActiveRecord
{
    const STATUS_TRASH = 10;
    const STATUS_DRAFT = 20;
    const STATUS_PUBLISH = 30;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slug'], 'unique'],
            [['title', 'slug', 'content', 'main_layout', 'partial_layout'], 'string'],
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [['content', 'main_layout', 'partial_layout'], 'default', 'value' => new Expression('NULL')],
            [
                ['status'],
                'in',
                'range' => array_keys(self::statusList()),
            ],
            [['publish_at'], 'date', 'type' => 'datetime', 'format' => 'MM/dd/yyyy hh:mm a',
                'timestampAttribute' => 'publish_at'],
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
            'title' => One::t('app', 'Title'),
            'slug' => One::t('app', 'Slug'),
            'content' => One::t('app', 'Content'),
            'status' => One::t('app', 'Status'),
            'publish_at' => One::t('app', 'Published On'),
            'created_by' => One::t('app', 'Created By'),
            'created_at' => One::t('app', 'Created At'),
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
        if ($this->status != self::STATUS_PUBLISH) {
            $this->publish_at = null;
        }

        return parent::beforeSave($insert);
    }

    /**
     * Get status.
     * You may specify a special key 'tag' in $htmlOptions. If it is specified it will be used instead of
     * 'span' tag. Because 'span' tag will be used as defaults.
     *
     * @return string|null
     */
    public function getStatus()
    {
        $list = self::statusList();

        if (isset($list[$this->status])) {
            $status = $list[$this->status];

            switch ($status) {
                case 'Publish':
                    $content = '<i class="ion-ios-checkmark-outline"></i> Publish on ' . $this->getPublishAt();
                    $class = 'text-success';
                    break;
                case 'Draft':
                    $content = '<i class="ion-ios-information-outline"></i> Draft, last modified ' . $this->getUpdatedAt();
                    $class = 'text-info';
                    break;
                case 'Trash':
                    $content = '<i class="ion-trash-b"></i> Trash, last modified ' . $this->getUpdatedAt();
                    $class = 'text-danger';
                    break;
            }

            return Html::tag('span', $content, ['class' => $class]);
        }

        return null;
    }

    /**
     * Get formatted date and time.
     *
     * @param string $format
     * @return string
     */
    public function getPublishAt($format = 'short')
    {
        return One::app()->formatter->asDatetime($this->publish_at, $format);
    }

    /**
     * Move to trash.
     * It will delete the item from the DB.
     */
    public function moveToTrash()
    {
        $this->status = self::STATUS_TRASH;
        $this->save(false);
    }

    /**
     * Record an old model as a revision.
     */
    public function recordRevision()
    {
        // TODO: Check before do revision weather the model allowed for Revision
        $revision = new Revision();
        $revision->model_id = $this->id;
        $revision->model = 'Page';
        $revision->title = $this->title;
        $revision->slug = $this->slug;
        $revision->content = $this->content;
        $revision->save();
    }

    /**
     * Get last autosaved.
     *
     * @return array|Revision|null
     */
    public function getLastAutosaved()
    {
        return Revision::find()->where([
            'type' => Revision::TYPE_AUTO_SAVE,
            'model_id' => $this->id,
            'model' => 'Page'
        ])->one();
    }

    /**
     * Get Revisions.
     *
     * @return array|Revision[]
     */
    public function getRevisions()
    {
        return $revisions = Revision::find()->where([
            'type' => Revision::TYPE_DEFAULT,
            'model_id' => $this->id,
            'model' => 'Page'
        ])->orderBy(['id' => SORT_ASC])->all();
    }

    /**
     * Get revision by created_at.
     *
     * @param $date
     * @return array|Revision|null
     */
    public function getRevisionByDate($date)
    {
        return Revision::find()->where([
            'created_at' => $date,
            'model_id' => $this->id,
            'model' => 'Page'
        ])->one();
    }

    /**
     * Load from the revision.
     *
     * @param $action null|string
     * @return array|Revision|null|void
     */
    public function loadFromRevision($action) {
        if ($action == null) {
            return;
        }

        $revision = $action == 'autosave' ? $this->getLastAutosaved() :
            $this->getRevisionByDate(str_replace('revision_', '', $action));

        if ($revision != null) {
            $this->title = $revision->title;
            $this->slug = $revision->slug;
            $this->content = $revision->content;
        }

        return $revision;
    }

    /**
     * @inheritdoc
     * 
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageQuery(get_called_class());
    }

    /**
     * Find by slug.
     *
     * @param $slug string
     * @return null|static
     */
    public static function findBySlug($slug)
    {
        return static::findOne(['slug' => $slug]);
    }

    /**
     * Helper Function
     *
     * @param bool $noTrash
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_PUBLISH => 'Publish',
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_TRASH => 'Trash',
        ];
    }
}
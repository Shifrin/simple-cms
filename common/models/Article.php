<?php

namespace common\models;

use One;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Article Class File.
 * This is the model class for table "{{%post}}".
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 *
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $summary
 * @property integer $status
 * @property integer $author_id
 * @property integer $publish_at
 * @property string $post_image
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Attachment[] $files
 * @property Category[] $categories
 */
class Article extends Posts
{
    public $category;
    private $_category;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'default', 'value' => 'Article'],
            [['slug'], 'unique'],
            [['title', 'slug', 'content', 'summary'], 'string'],
            [['content', 'summary'], 'default', 'value' => new Expression('NULL')],
            [
                ['category'],
                'in',
                'range' => array_keys(Category::allList()),
                'allowArray' => true
            ],
            [['status'], 'default', 'value' => self::STATUS_DRAFT],
            [
                ['status'],
                'in',
                'range' => array_keys(self::statusList()),
            ],
            [['author_id'], 'in', 'range' => array_keys(self::authorList())],
            [['publish_at'], 'date', 'type' => 'datetime', 'format' => 'MM/dd/yyyy HH:mm a',
                'timestampAttribute' => 'publish_at'],
            ['post_image', 'url'],
            ['post_image', 'default', 'value' => new Expression('NULL')],
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
            'summary' => One::t('app', 'Summary'),
            'status' => One::t('app', 'Status'),
            'author_id' => One::t('app', 'Author'),
            'publish_at' => One::t('app', 'Publish Date & Time'),
            'post_image' => One::t('app', 'Post Image'),
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
     * Get files.
     *
     * @return $this
     */
    public function getFiles()
    {
        return $this->hasMany(Attachment::className(), ['id' => 'attachment_id'])
            ->viaTable('{{%attachment_relation}}', ['model_id' => 'id'], function ($query) {
                /* @var $query ActiveQuery */
                $query->andWhere(['model' => 'Article']);
            });
    }

    /**
     * Get Categories.
     *
     * @return $this
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable('{{%category_relation}}', ['model_id' => 'id'], function ($query) {
                /* @var $query ActiveQuery */
                $query->andWhere(['{{%category_relation}}.type' => 'Article']);
            });
    }

    /**
     * Get category.
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->_category = ArrayHelper::map($this->categories, 'id', 'name');
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) $this->linkCategory();
    }

    /**
     * Add relation between model and category.
     *
     * @return bool
     */
    public function linkCategory()
    {
        if ($this->validate(['category'])) {
            $category = $this->category;

            if (isset($category[0])) {
                return One::app()->db->createCommand()->insert('{{%category_relation}}', [
                    'category_id' => $category[0],
                    'model_id' => $this->id,
                    'type' => 'Article'
                ])->execute();
            } else {
                return false;
            }
        }
    }

    /**
     * Remove relation between model and category.
     *
     * @return bool
     */
    public function unlinkCategory()
    {
        if ($this->validate(['category'])) {
            $category = $this->category;

            if (isset($category[0])) {
                return One::app()->db->createCommand()->delete('{{%category_relation}}', 'category_id=:cid AND model_id=:tid AND type=:type', [
                    ':cid' => $category[0], ':tid' => $this->id, ':type' => 'Article'
                ])->execute();
            } else {
                return false;
            }
        }
    }

    /**
     * @inheritdoc
     * @return PageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }
}
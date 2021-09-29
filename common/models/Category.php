<?php

namespace common\models;

use One;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%category}}".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property CategoryRelation[] $categoryRelations
 * @property Category $parent
 * @property Category[] $children
 * @property Image[] $images
 */
class Category extends \common\app\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['slug'], 'unique'],
            [['parent_id'], 'in', 'range' => function() {
                return array_keys(self::parentList());
            }],
            [['parent_id'], 'default', 'value' => 0],
            [['parent_id'], 'integer'],
            [['description', 'slug'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className()
            ],
            [
                'class' => BlameableBehavior::className()
            ],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'immutable' => true,
                'ensureUnique' => true,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => One::t('app', 'ID'),
            'parent_id' => One::t('app', 'Parent'),
            'name' => One::t('app', 'Name'),
            'description' => One::t('app', 'Description'),
            'slug' => One::t('app', 'Slug'),
            'created_at' => One::t('app', 'Created At'),
            'created_by' => One::t('app', 'Created By'),
            'updated_at' => One::t('app', 'Updated At'),
            'updated_by' => One::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryRelations()
    {
        return $this->hasMany(CategoryRelation::className(), ['category_id' => 'id']);
    }

    /**
     * Get Parent Category of current category.
     *
     * @return ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }

    /**
     * Get children categories of the current category.
     *
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(self::className(), ['parent_id' => 'id']);
    }

    /**
     * Get related images of the category.
     *
     * @return $this
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['id' => 'model_id'])->viaTable('{{%category_relation}}', [
            'category_id' => 'id'
        ], function ($query) {
            /* @var $query ActiveQuery */
            $query->andWhere(['type' => 'Image']);
        });
    }

    /**
     * Get related articles of the category.
     *
     * @return $this
     */
    public function getArticles()
    {
        return $this->hasMany(Image::className(), ['id' => 'model_id'])->viaTable('{{%category_relation}}', [
            'category_id' => 'id'
        ], function ($query) {
            /* @var $query ActiveQuery */
            $query->andWhere(['type' => 'Article']);
        });
    }

    /**
     * Get related news of the category.
     *
     * @return $this
     */
    public function getNews()
    {
        return $this->hasMany(Image::className(), ['id' => 'model_id'])->viaTable('{{%category_relation}}', [
            'category_id' => 'id'
        ], function ($query) {
            /* @var $query ActiveQuery */
            $query->andWhere(['type' => 'News']);
        });
    }

    /**
     * Get thumbnail image.
     * Thumbnail image will be the latest Image post's image.
     *
     * @param string $size
     * @param array $options
     * @return null|string
     */
    public function getThumbnail($size = 'default', $options = [])
    {
        $image = $this->getImages()->orderBy([
            'id' => SORT_DESC
        ])->one();

        if ($image) {
            $thumb = $image->getThumbnail($size, $options);
        } else {
            $thumb = Html::img(Url::to(['images/no-thumb.jpg']), $options);
        }

        return $thumb;
    }

    /**
     * @inheritdoc
     *
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * Helper function to get parent categories list.
     *
     * @return array
     */
    public static function parentList()
    {
        return ArrayHelper::map(self::find()->where(['parent_id' => 0])->select(['id', 'name'])
            ->asArray()->all(), 'id', 'name');
    }

    /**
     * Helper function to get all category list.
     *
     * @return array
     */
    public static function allList()
    {
        return ArrayHelper::map(self::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name');
    }
}

<?php

namespace common\models;

use One;
use common\app\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * News Class File.
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
 */
class Posts extends ActiveRecord
{
    const STATUS_TRASH = 10;
    const STATUS_DRAFT = 20;
    const STATUS_PUBLISH = 30;
    const STATUS_PENDING = 40;

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
            [['type'], 'default', 'value' => 'News'],
            [['slug'], 'unique'],
            [['title', 'slug', 'content', 'summary'], 'string'],
            [['content', 'summary'], 'default', 'value' => new Expression('NULL')],
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

    /**
     * Get the title with truncate support.
     *
     * @param int $length
     * @param string $suffix
     * @param null $encoding
     * @param bool $asHtml
     * @return string
     */
    public function getTitle($length = 0, $suffix = '...', $encoding = null, $asHtml = false)
    {
        if ($length > 0) {
            return StringHelper::truncate($this->title, $length, $suffix = '...', $encoding = null, $asHtml = false);
        }

        return $this->title;
    }

    /**
     * Get status.
     * You may specify a special key 'tag' in $htmlOptions. If it is specified it will be used instead of
     * 'span' tag. Because 'span' tag will be used as defaults.
     *
     * @param array $htmlOptions if not empty generated Html will be returned.
     * @return string|null
     */
    public function getStatus($htmlOptions = [])
    {
        $list = self::statusList();

        if (isset($list[$this->status])) {
            if (empty($htmlOptions)) {
                return $list[$this->status];
            }

            $status = $list[$this->status];

            switch ($status) {
                case 'Publish':
                    $icon = '<i class="ion-ios-checkmark-outline"></i> ';
                    $class = 'label label-success';
                    break;
                case 'Pending':
                    $icon = '<i class="ion-information-circled"></i> ';
                    $class = 'label label-warning';
                    break;
                case 'Draft':
                    $icon = '<i class="ion-ios-information-outline"></i> ';
                    $class = 'label label-info';
                    break;
                case 'Trash':
                    $icon = '<i class="ion-trash-b"></i> ';
                    $class = 'label label-danger';
                    break;
            }

            $tag = isset($htmlOptions['tag']) ? $htmlOptions['$htmlOptions'] : 'span';
            $htmlOptions['class'] = isset($htmlOptions['class']) ? "{$class} " . $htmlOptions['class'] : $class;

            unset($htmlOptions['tag']);
            return Html::tag($tag, $icon . $status, $htmlOptions);
        }

        return null;
    }

    /**
     * Get summary.
     *
     * @param int $length
     * @param string $suffix
     * @param null $encoding
     * @param bool $asHtml
     * @return string
     */
    public function getSummary($length = 0, $suffix = '...', $encoding = null, $asHtml = false)
    {
        $summary = $this->summary;

        if ($summary == null) {
            $doc = new \DOMDocument();
            $doc->loadHTML($this->content);

            foreach ($doc->getElementsByTagName('p') as $p) {
                $summary = $p->nodeValue;
                break;
            }
        }

        if ($length > 0) {
            return StringHelper::truncate($summary, $length, $suffix = '...', $encoding = null, $asHtml = false);
        }

        return $summary;
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
     * Get post image of a News Model.
     *
     * @param array $options Html Options
     * @return null|string
     */
    public function getPostImage($options = [])
    {
        $image = $this->post_image;

        if ($image !== null) {
            $imageInfo = getimagesize(Html::encode($image));
            $options = array_merge($options, [
                'width' => $imageInfo[0],
                'height' => $imageInfo[1]
            ]);
            return Html::img($image, array_merge(['alt' => $this->title], $options));
        }

        return Html::img(Url::to(['images/no-thumb.jpg']), array_merge([
            'alt' => $this->title
        ], $options));
    }

    /**
     * Get first image in the content if available.
     *
     * @return null|string
     */
    public function getContentFirstImage()
    {
        if (!empty($this->content)) {
            $doc = new \DOMDocument();
            $doc->loadHTML($this->content);

            foreach ($doc->getElementsByTagName('img') as $image) {
                return Html::img($image->getAttribute('src'), [
                    'class' => $image->getAttribute('class'),
                    'width' => $image->getAttribute('width'),
                    'height' => $image->getAttribute('height'),
                    'alt' => $image->getAttribute('alt'),
                ]);
            }
        }

        return null;
    }

    /**
     * Get author model.
     *
     * @return User|null
     */
    public function getAuthor()
    {
        return \One::app()->user->identity->findIdentity($this->author_id);
    }

    /**
     * Author name.
     *
     * @return null|string
     */
    public function getAuthorName()
    {
        $author = $this->getAuthor();

        if ($author) {
            return $author->getName();
        }

        return null;
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
     * Find by slug.
     *
     * @param $slug string
     * @return null|static
     */
    public static function findBySlug($slug)
    {
        return static::findOne(['slug' => $slug, 'status' => self::STATUS_PUBLISH]);
    }

    /**
     * Helper function.
     *
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_PUBLISH => 'Publish',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_TRASH => 'Trash',
        ];
    }

    /**
     * Helper function.
     *
     * @return array
     */
    public static function authorList()
    {
        $authors = User::find()->with('profile')->active()->all();

        foreach ($authors as $author) {
            $author->username = $author->getName();
        }

        return ArrayHelper::map($authors, 'id', 'username');
    }
}
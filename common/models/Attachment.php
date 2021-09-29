<?php

namespace common\models;

use One;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use common\app\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "app_attachment".
 *
 * @property integer $id
 * @property string $name
 * @property string $ext
 * @property integer $size
 * @property string $type
 * @property string $path
 * @property string $thumbnail_size
 * @property string $meta_data
 * @property string $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 */
class Attachment extends ActiveRecord
{
    const STATUS_PROTECTED = 10;
    const STATUS_PUBLIC = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attachment}}';
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
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ext', 'size', 'type'], 'required'],
            [['path', 'thumbnail_size', 'meta_data'], 'string'],
            [['size', 'status'], 'integer'],
            [['status'], 'default', 'value' => self::STATUS_PUBLIC],
            [['status'], 'in', 'range' => array_keys(self::statusList())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => One::t('app', 'ID'),
            'name' => One::t('app', 'Name'),
            'ext' => One::t('app', 'Extension'),
            'size' => One::t('app', 'Size'),
            'type' => One::t('app', 'Type'),
            'path' => One::t('app', 'File Location'),
            'thumbnail_size' => One::t('app', 'Thumbnail Size'),
            'meta_data' => One::t('app', 'Meta Data'),
            'status' => One::t('app', 'Status'),
            'created_at' => One::t('app', 'Created At'),
            'created_by' => One::t('app', 'Created By'),
            'updated_at' => One::t('app', 'Updated At'),
            'updated_by' => One::t('app', 'Updated By'),
        ];
    }

    /**
     * @inheritdoc
     * @return AttachmentQuery
     */
    public static function find()
    {
        return new AttachmentQuery(get_called_class());
    }

    /**
     * Get file url.
     * If file is an image it will be published to assets directory and return file link.
     *
     * @return null|string
     */
    public function getFileUrl()
    {
        if ($this->status == self::STATUS_PROTECTED) {
            $file = $this->findFile();

            if (strpos($this->type, 'image') !== false) {
                $published = One::app()->assetManager->publish($file, 'images');
                return end($published);
            } else {
                return Url::to(['/file/download', 'id' => $this->id]);
            }
        } else {
            $uploadedUrl = Url::to(One::getAlias('@web/uploads'));
            $fileUrl = $uploadedUrl . $this->path == null ? "/{$this->name}.{$this->ext}" :
                "/{$this->path}/{$this->name}.{$this->ext}";

            return $fileUrl;
        }

        return null;
    }

    /**
     * Get file path, if file not found it will return false value.
     *
     * @param null $uploadedDir
     * @return bool|string
     */
    public function getFilePath($uploadedDir = null)
    {
        return $this->findFile($uploadedDir);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->deleteFile();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete file.
     *
     * @return bool
     */
    public function deleteFile()
    {
        $file = $this->findFile();
        return !$file ? false : unlink($file);
    }

    /**
     * Find the file using the file name.
     *
     * @param null $uploadedDir
     * @return bool|string
     */
    public function findFile($uploadedDir = null)
    {
        $uploadDir = $uploadedDir !== null ?: One::getAlias('@webroot/uploads');
        $path = $this->path == null ? "{$this->name}.{$this->ext}" :
            $this->path . DIRECTORY_SEPARATOR . "{$this->name}.{$this->ext}";

        $file = FileHelper::normalizePath($uploadDir . DIRECTORY_SEPARATOR . $path);

        if (file_exists($file)) {
            return $file;
        }

        return false;
    }

    /**
     * Helper function.
     *
     * @return array
     */
    public static function statusList()
    {
        return [
            self::STATUS_PROTECTED => 'Protected',
            self::STATUS_PUBLIC => 'Publish',
        ];
    }

    /**
     * Generate random string.
     *
     * @param integer $length defaults to 10
     * @return string generated password
     */
    public static function generateRandomString($length = 10)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $string = substr(str_shuffle($chars), 0, $length);

        return strtolower($string);
    }

    public function isImage()
    {
        if (strpos($this->type, 'image') !== false) {
            return true;
        }

        return false;
    }
}

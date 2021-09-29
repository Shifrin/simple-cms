<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 01/12/2016
 * Time: 1:04 PM
 */

namespace common\components;


use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

class FileManager extends Component
{

    /**
     * Allowed file extensions to upload
     * @var array
     */
    public $allowedFileExtensions = ['jpg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
    /**
     * Allowed maximum upload size per file
     * It's should be provide in Bytes
     * @var int Defaults to 1048576 (1MB)
     */
    public $allowedMaxFileSize = 1048576;
    /**
     * Upload directory
     * @var string Defaults to @backendWebroot/uploads
     */
    public $uploadDirectory = '@backendWebroot/uploads';
    /**
     * Protected Upload directory
     * @var string Defaults to @backend/runtime/uploads
     */
    public $protectedUploadDirectory = '@backend/runtime/uploads';
    /**
     * Thumbnail sizes for images
     * Ex:
     * ~~~
     * [
     *      'default' => [150, 150], // width, height
     *      'medium' => [300, 300]
     * ]
     * ~~~
     * @var array
     */
    public $thumbnailSizes = [];
    /**
     * Default thumbnail size
     * This is will be used if no $thumbnailSizes provided.
     * This is can be override by providing default size in $thumbnailSizes
     * @var array
     */
    private $_defaultThumbnailSize = [
        400, 250
    ];
    /**
     * Watermark image location
     * @var string Defaults to @webroot/images/watermark.png
     */
    public $watermarkImage = '@backendWebroot/images/watermark.png';
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        FileHelper::createDirectory($this->getUploadDirectory(), 0755, false);
        FileHelper::createDirectory($this->getProtectedUploadDirectory(), 0755, false);
        $this->thumbnailSizes['default'] = $this->_defaultThumbnailSize;

        $watermarkImage = \One::getAlias($this->watermarkImage);

        if (!file_exists($watermarkImage)) {
            throw new InvalidConfigException('Given watermark image "$location" not found.');
        } else {
            $this->watermarkImage = $watermarkImage;
        }
    }

    /**
     * Get upload directory.
     *
     * @return bool|string
     */
    public function getUploadDirectory()
    {
        return \One::getAlias($this->uploadDirectory);
    }

    /**
     * Get temporary directory.
     *
     * @return bool|string
     */
    public function getProtectedUploadDirectory()
    {
        return \One::getAlias($this->protectedUploadDirectory);
    }

    /**
     * Get watermark image location.
     *
     * @return bool|string
     * @throws InvalidConfigException
     */
    public function getWatermarkImage()
    {
        return FileHelper::normalizePath($this->watermarkImage);
    }
}
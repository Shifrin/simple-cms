<?php
/**
 * AssetManager Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */


namespace app\components;

use yii\helpers\FileHelper;
use yii\base\InvalidParamException;


class AssetManager extends \yii\web\AssetManager
{
    /**
     * @var array published assets
     */
    private $_published;

    /**
     * @inheritdoc
     * @param string $path
     * @param null|string $dirName
     * @param array $options
     * @return array
     */
    public function publish($path, $dirName = null, $options = [])
    {
        $path = \Yii::getAlias($path);

        if (isset($this->_published[$path])) {
            return $this->_published[$path];
        }

        if (!is_string($path) || ($src = realpath($path)) === false) {
            throw new InvalidParamException("The file or directory to be published does not exist: $path");
        }

        if (is_file($src)) {
            return $this->_published[$path] = $this->publishFile($src, $dirName);
        } else {
            return $this->_published[$path] = $this->publishDirectory($src, $options);
        }
    }

    /**
     * @inheritdoc
     * @param string $src
     * @param string $dirName
     * @return array
     * @throws \yii\base\Exception
     */
    protected function publishFile($src, $dirName)
    {
        $dir = $this->hash($src);

        if ($dirName !== null) {
            $dir = $dirName;
        }

        $fileName = basename($src);
        $dstDir = $this->basePath . DIRECTORY_SEPARATOR . $dir;
        $dstFile = $dstDir . DIRECTORY_SEPARATOR . $fileName;

        if (!is_dir($dstDir)) {
            FileHelper::createDirectory($dstDir, $this->dirMode, true);
        }

        if ($this->linkAssets) {
            if (!is_file($dstFile)) {
                symlink($src, $dstFile);
            }
        } elseif (@filemtime($dstFile) < @filemtime($src)) {
            copy($src, $dstFile);
            if ($this->fileMode !== null) {
                @chmod($dstFile, $this->fileMode);
            }
        }

        return [$dstFile, $this->baseUrl . "/$dir/$fileName"];
    }
}
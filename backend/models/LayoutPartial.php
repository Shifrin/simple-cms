<?php
namespace backend\models;

use yii\helpers\FileHelper;

/**
 * LayoutPartial Class File
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 */
class LayoutPartial extends Layout
{
    /**
     * Used to save found layout files temporarily.
     *
     * @var array
     */
    protected static $_files = [];
    /**
     * @inheritdoc
     */
    public function init()
    {
        self::$_path = \One::app()->layoutManager->getPartialLayoutsPath();
        self::$_files = \One::app()->layoutManager->getPartialLayouts();
    }

    /**
     * Get sample file
     *
     * @return string
     */
    public function getSample()
    {
        // Theme sample file
        $sampleFile = \One::app()->view->theme->basePath . '/layout/sample-partial.php';

        // If theme sample not available use the default
        if (!file_exists($sampleFile)) {
            $sampleFile = \One::app()->controller->viewPath . '/sample-partial.php';
        }

        return $sampleFile;
    }

    /**
     * Find a layout.
     *
     * @param $slug
     * @return mixed|null
     */
    public static function find($slug)
    {
        if (empty(self::$_files)) {
            self::init();
        }

        if (array_key_exists($slug, self::$_files)) {
            return self::$_path . '/' . self::$_files[$slug];
        }

        return null;
    }

    /**
     * This is a helper function to get all partial layouts list.
     *
     * @return array
     */
    public static function allList()
    {
        $keys = array_keys(\One::app()->layoutManager->getPartialLayouts());
        $list = [];

        foreach ($keys as $key) {
            $list[$key] = ucfirst($key);
        }

        return $list;
    }
}
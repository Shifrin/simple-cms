<?php
namespace backend\models;

use yii\base\ErrorException;
use yii\base\Model;
use yii\helpers\Inflector;

/**
 * Layout Model Class File
 *
 * This will help to create layout files for the frontend.
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 */
class Layout extends Model
{
    /**
     * Name of the layout.
     *
     * @var string
     */
    public $name;
    /**
     * Content of the layout.
     * Mostly known as Codes.
     *
     * @var string
     */
    public $content;
    /**
     * Layouts save path.
     *
     * @var string
     */
    protected static $_path = null;
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
        self::$_path = \One::app()->layoutManager->getMainLayoutsPath();
        self::$_files = \One::app()->layoutManager->getMainLayouts();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'required'],
            [['name', 'content'], 'string'],
        ];
    }

    /**
     * Save layout.
     *
     * @return mixed
     * @throws ErrorException
     */
    public function save()
    {
        if ($this->validate()) {
            $path = self::$_path;
            $name = Inflector::camel2id($this->name) . '.php';

            if (!$handle = fopen("$path/$name", 'w')) {
                throw new ErrorException("Unable to create a layout file.");
            }

            if (fwrite($handle, $this->content) === false) {
                throw new ErrorException("Unable to write content to a layout file.");
            }

            return fclose($handle);
        }
    }

    /**
     * Get sample file
     *
     * @return string
     */
    public function getSample()
    {
        // Theme sample file
        $sampleFile = \One::app()->view->theme->basePath . '/layout/sample-main.php';

        // If theme sample not available use the default
        if (!file_exists($sampleFile)) {
            $sampleFile = \One::app()->controller->viewPath . '/sample-main.php';
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
     * This is a helper function to get all layouts list.
     *
     * @return array
     */
    public static function allList()
    {
        $keys = array_keys(\One::app()->layoutManager->getMainLayouts());
        $list = [];

        foreach ($keys as $key) {
            $list[$key] = ucfirst($key);
        }

        return $list;
    }
}
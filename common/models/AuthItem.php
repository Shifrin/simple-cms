<?php
namespace common\models;

use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\rbac\DbManager;

/**
 * AuthItem Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */
abstract class AuthItem extends Model
{
    /**
     * Auth Manager.
     *
     * @var DbManager
     */
    protected $_authManager;
    public $parent;
    public $name;
    public $type;
    public $description;
    public $rule_name;
    public $data;
    public $_isNewRecord = true;
    public $_oldName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_authManager = \One::app()->authManager;
        parent::init();
    }

    /**
     * Using to save Item to the database and it should be inherit.
     */
    public function save()
    {

    }

    /**
     * Create a Authorization Item Rule.
     *
     * @return string|null
     */
    protected function createRule()
    {
        if (!empty($this->rule_name)) {
            $class = $this->rule_name;

            if (class_exists($class)) {
                $rule = new $class;
                $getRule = $this->_authManager->getRule($rule->name);

                if ($getRule == null) {
                    $this->_authManager->add($rule);
                }

                return $rule->name;
            }
        }

        return null;
    }

    public static function getParents($name)
    {
        return (new Query)->from(\One::app()->authManager->itemChildTable)
            ->where(['child' => $name])->all(\One::app()->authManager->db);
    }

//    public static function hasParent($name)
//    {
//        return (new Query)->from(\One::app()->authManager->itemChildTable)
//            ->where(['child' => $name])->one(\One::app()->authManager->db) !== false;
//    }

    /**
     * Helper function to generate dropdown list in forms.
     *
     * @return array
     */
    public static function ruleList()
    {
        $dir = \One::getAlias('@common/rules');
        $files = FileHelper::findFiles($dir, ['only' => ['*Rule.php']]);
        $namespace = '\\common\\rules\\';
        $list = [];

        foreach($files as $file) {
            $explode = explode('\\', $file);
            $ext = end($explode);
            $fileName = str_replace('.php', '', $ext);
            $className = $namespace . $fileName;
            $class = new $className;
            $list[$className] = $class->name;
        }

        return $list;
    }

    public static function permissionList()
    {
        return ArrayHelper::map(\One::app()->authManager->getPermissions(), 'name', 'name');
    }

    public static function roleList()
    {
        return ArrayHelper::map(\One::app()->authManager->getRoles(), 'name', 'name');
    }
}
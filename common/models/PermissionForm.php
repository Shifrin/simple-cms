<?php
namespace common\models;

use yii\rbac\Item;

/**
 * PermissionForm Class File.
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */
class PermissionForm extends AuthItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'checkUniqueness'],
//            [
//                ['parent'],
//                'in',
//                'range' => self::permissionList(),
//                'message' => "Permission '{value}' is not exist in the provided list"
//            ],
            [['type'], 'default', 'value' => Item::TYPE_PERMISSION],
            [['description', 'rule_name', 'data'], 'default', 'value' => null],
            [['parent', 'description', 'rule_name', 'data'], 'string'],
            [
                ['rule_name'],
                'in',
                'range' => array_keys(self::ruleList()),
                'message' => "Rule '{value}' is not exist in the provided list"
            ]
        ];
    }

    /**
     * Check uniqueness of the given name.
     *
     * @param $attribute string
     * @param $params array
     */
    public function checkUniqueness($attribute, $params)
    {
        $permissions = $this->_authManager->getPermissions();

        if ($this->_isNewRecord && array_key_exists($this->$attribute, $permissions)) {
            $this->addError($attribute, \One::t('app', 'Given permission name already exist.'));
        }
    }

    /**
     * Add to the database.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            if ($this->_isNewRecord) {
                $permission = $this->_authManager->createPermission($this->name);
            } else {
                $permission = $this->_authManager->getPermission($this->_oldName);
                $permission->name = $this->name;
            }

            $permission->description = $this->description;
            $permission->ruleName = $this->createRule();
            $permission->data = $this->data !== null ? serialize($this->data) : null;

            if ($this->_isNewRecord) {
                $this->_authManager->add($permission);
            } else {
                $this->_authManager->update($this->_oldName, $permission);
            }

            if ($this->parent !== '') {
                $parent = $this->_authManager->getPermission($this->parent);
                $this->_authManager->addChild($parent, $permission);
            }

            return true;
        }

        return false;
    }

    public static function getParent($name)
    {
        $parents = self::getParents($name);

        foreach ($parents as $key => $parent) {
            $item = \One::app()->authManager->getPermission($parent['parent']);

            if ($item !== null) {
                return $item->name;
                break;
            }
        }

        return null;
    }

    public static function permissionNestedList()
    {
        $authManager = \One::app()->authManager;
        $permissions = $authManager->getPermissions();
        $list = [];

        foreach ($permissions as $name => $permission) {
            $child = $authManager->getChildren($name);

            if (!empty($child)) {
                $list[$name] = ['parent' => $permission, 'child' => $child];
                $permissions = array_diff_key($permissions, $child);
            } else {
                $parents = self::getParents($name);
                $hasParent = false;

                foreach ($parents as $key => $childArray) {
                    if (array_key_exists($childArray['parent'], $permissions)) {
                        $hasParent = true;
                        break;
                    }
                }

                if ($hasParent) {
                    continue;
                } else {
                    $list[$name] = ['parent' => $permission];
                }
            }
        }

        return $list;
    }
}
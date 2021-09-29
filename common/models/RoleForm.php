<?php
namespace common\models;

use yii\rbac\Item;

/**
 * RoleForm Class File.
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */
class RoleForm extends AuthItem
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
            [['type'], 'default', 'value' => Item::TYPE_ROLE],
            [['parent', 'description', 'rule_name', 'data'], 'default', 'value' => null],
            [['parent', 'description', 'rule_name', 'data'], 'string'],
            [
                ['rule_name'],
                'in',
                'range' => self::ruleList(),
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
        $item = $this->_authManager->getRole($this->$attribute);

        if ($item == null) {
            $item = $this->_authManager->getPermission($this->$attribute);
        }

        if ($this->_isNewRecord && $item !== null) {
            $this->addError($attribute, \One::t('app', 'Given role name already exist.'));
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
                $role = $this->_authManager->createRole($this->name);
            } else {
                $role = $this->_authManager->getRole($this->_oldName);
                $role->name = $this->name;
            }

            $role->description = $this->description;
            $role->ruleName = $this->createRule();
            $role->data = $this->data !== null ? serialize($this->data) : null;

            if ($this->_isNewRecord) {
                $this->_authManager->add($role);
            } else {
                $this->_authManager->update($this->_oldName, $role);
            }

            if ($this->parent !== '') {
                $parent = $this->_authManager->getRole($this->parent);
                $this->_authManager->addChild($parent, $role);
            }

            return true;
        }

        return false;
    }

    public static function getParent($name)
    {
        $parents = self::getParents($name);

        foreach ($parents as $key => $parent) {
            $item = \One::app()->authManager->getRole($parent['parent']);

            if ($item !== null) {
                return $item->name;
                break;
            }
        }

        return null;
    }
}
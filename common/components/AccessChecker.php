<?php
/**
 * AccessChecker Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */


namespace common\components;


use yii\rbac\CheckAccessInterface;

class AccessChecker implements CheckAccessInterface
{
    /**
     * @inheritdoc
     */
    public function checkAccess($userId, $permissionName, $params = [])
    {

    }
}
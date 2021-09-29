<?php
namespace common\rules;

use common\models\AuthItem;
use common\models\UserProfile;
use yii\rbac\Rule;

/**
 * UserProfileRule Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project Arab Photo Store
 * @copyright 2016 Mohammed Shifreen
 */
class UserProfileRule extends Rule
{
    public $name = 'Profile Owner';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        $access = isset($params['profile']) ? $params['profile']->user_id === $user : false;

        if (!$access) {
            $parentRole = null;
            $assignedRoles = \One::app()->authManager->getRolesByUser($user);

            foreach ($assignedRoles as $key => $role) {
                $parentRoles = AuthItem::getParents($key);
                $parentRole = !empty($parentRoles) ? $parentRoles[0]['parent'] : $key;
                break;
            }

            if ($parentRole !== null) {
                $access = \One::app()->authManager->checkAccess($user, $parentRole, $params);
            }
        }

        return $access;
    }

}
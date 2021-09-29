<?php

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $permissionDataProvider yii\data\ActiveDataProvider */
/* @var $roleDataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
\yii\bootstrap\Modal::begin([
    'header' => '<h4 id="modal-title">' . One::t('app', 'Assigned Permissions') . '</h4>',
    'options' => [
        'id' => 'assignment-modal'
    ],
    'size' => 'modal-lg'
]);
?>

<div id="modal-content"></div>

<div class="overlay-wrapper">
    <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
</div>

<?php \yii\bootstrap\Modal::end(); ?>


<div class="user-index">
    <?= \yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => One::t('app', '{icon} Users', ['icon' => '<i class="fa fa-user"></i>']),
                'content' => $this->render('_grid', ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]),
                'active' => One::app()->request->get('tab') == 'users' || null ? true : false,
                'visible' => \One::app()->user->can('User'),
                'encode' => false
            ],
            [
                'label' => One::t('app', '{icon} Roles', ['icon' => '<i class="ion-person-stalker"></i>']),
                'content' => $this->render('_rolegrid', ['dataProvider' => $roleDataProvider]),
                'active' => One::app()->request->get('tab') == 'roles' ? true : false,
                'visible' => \One::app()->user->can('Roles'),
                'encode' => false
            ],
            [
                'label' => One::t('app', '{icon} Permissions', ['icon' => '<i class="ion-android-checkmark-circle"></i>']),
                'content' => $this->render('_permissiongrid', ['dataProvider' => $permissionDataProvider]),
                'active' => One::app()->request->get('tab') == 'permissions' ? true : false,
                'visible' => \One::app()->user->can('Permissions'),
                'encode' => false
            ],
        ],
    ]) ?>
</div>

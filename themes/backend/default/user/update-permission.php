<?php

/* @var $this yii\web\View */
/* @var $model common\models\PermissionForm */

$this->title = One::t('app', 'Update Permission');
$this->params['breadcrumbs'][] = [
    'label' => One::t('app', 'Permissions'), 'url' => ['index', 'tab' => 'permissions']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-permission">

    <?= $this->render('_permissionform', ['model' => $model]) ?>

</div>

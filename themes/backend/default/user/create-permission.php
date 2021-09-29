<?php

/* @var $this yii\web\View */
/* @var $model common\models\PermissionForm */

$this->title = One::t('app', 'Create Permission');
$this->params['breadcrumbs'][] = [
    'label' => One::t('app', 'Permissions'), 'url' => ['index', 'tab' => 'permissions']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="create-permission">

    <?= $this->render('_permissionform', ['model' => $model]) ?>

</div>

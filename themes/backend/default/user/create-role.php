<?php

/* @var $this yii\web\View */
/* @var $model common\models\RoleForm */

$this->title = One::t('app', 'Create Role');
$this->params['breadcrumbs'][] = [
    'label' => One::t('app', 'Roles'), 'url' => ['index', 'tab' => 'roles']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="create-role">

    <?= $this->render('_roleform', ['model' => $model]) ?>

</div>

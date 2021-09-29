<?php
/* @var $this yii\web\View */
/* @var $model \backend\models\Layout */

$this->title = \One::t('app', 'Create New Layout');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Layouts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="layout-create">

    <?= $this->render('_form', ['model' => $model]); ?>

</div>

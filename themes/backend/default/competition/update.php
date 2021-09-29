<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Competition',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Competition'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="competition-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

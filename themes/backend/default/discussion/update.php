<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Discussion',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Discussions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="discussion-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

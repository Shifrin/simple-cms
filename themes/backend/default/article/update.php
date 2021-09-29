<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Article',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="article-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

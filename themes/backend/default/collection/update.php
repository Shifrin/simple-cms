<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Collection',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collection'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="collection-update">

    <?= $this->render('_form', [
        'model' => $model,
        'auction' => $auction,
    ]) ?>

</div>

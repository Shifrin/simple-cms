<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= \yii\helpers\Html::a('<i class="ion-ios-plus-outline"></i> ' . \One::t('app', 'Create New'), ['create'], [
            'class' => 'btn btn-success',
        ]) ?>
    </p>

    <?= $this->render('_grid', [
        'dataProvider' => $dataProvider, 'searchModel' => $searchModel
    ]) ?>
</div>
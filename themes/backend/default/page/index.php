<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index">

    <p>
        <?= \yii\helpers\Html::a('<i class="ion-ios-plus-outline"></i> ' . \One::t('app', 'Create New'), ['create'], [
            'class' => 'btn btn-success',
        ]) ?>
    </p>

    <?= \yii\bootstrap\Tabs::widget([
        'items' => [
            [
                'label' => One::t('app', 'All'),
                'url' => ['index'],
                'content' => $this->render('_grid', [
                    'dataProvider' => $dataProvider, 'searchModel' => $searchModel
                ]),
                'active' => One::app()->request->get('tab') == null ? true : false,
                'encode' => false
            ],
            [
                'label' => One::t('app', '{icon} Trash', ['icon' => '<i class="glyphicon glyphicon-trash"></i>']),
                'url' => ['index', 'tab' => 'trash'],
                'content' => $this->render('_trashgrid', [
                    'dataProvider' => $dataProvider, 'searchModel' => $searchModel
                ]),
                'active' => One::app()->request->get('tab') == 'trash' ? true : false,
                'encode' => false
            ],
        ],
    ]) ?>
</div>

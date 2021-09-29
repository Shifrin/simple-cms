<?php
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel common\models\UserSearch */
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<p>
    <?= \yii\helpers\Html::a('<i class="ion-ios-plus-outline"></i> ' . \One::t('app', 'Create New'), ['create'], [
        'class' => 'btn btn-success',
    ]) ?>
</p>

<?php \yii\widgets\Pjax::begin(['id' => 'user-grid']); ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => '
        <div class="box-header">
            <h3 class="box-title">'. Yii::t("app", "User List") .'</h3>
            <div class="box-tools pull-right">{summary}</div>
        </div>
        <div class="box-body table-responsive">{items}</div>
        <div class="box-footer clearfix">{pager}</div>
        <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
    ',
    'options' => [
        'class' => 'box box-primary grid-view'
    ],
    'summaryOptions' => [
        'class' => 'label label-primary summary'
    ],
    'tableOptions' => [
        'class' => 'table table-bordered table-hover'
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'username',
        'email',
        [
            'attribute' => 'status',
            'format' => 'text',
            'value' => function($model) {
                return $model->getStatus();
            }
        ],

        [
            'class' => 'common\grid\ActionColumn',
            'contentOptions' => [
                'class' => 'actions',
            ],
            'buttonOptions' => [
                'data-toggle' => 'tooltip',
                'data-placement' => 'top'
            ]
        ],
    ],
]); ?>

<?php \yii\widgets\Pjax::end(); ?>
<?php
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel common\models\CollectionSearch */
?>

<?php \yii\widgets\Pjax::begin(['id' => 'collection-grid']); ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => '
        <div class="box-header">
            <h3 class="box-title">'. Yii::t("app", "Collection List") .'</h3>
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

        [
            'attribute' => 'title',
            'format' => 'html',
            'value' => function ($model) {
                return \yii\helpers\Html::a($model->title, ['update', 'id' => $model->id]);
            }
        ],
        'slug',
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function($model) {
                return $model->getStatus();
            }
        ],

        [
            'class' => 'common\grid\ActionColumn',
            'contentOptions' => [
                'class' => 'actions',
            ],
            'template' => '{update} {delete}',
            'buttonOptions' => [
                'data-toggle' => 'tooltip',
                'data-placement' => 'top'
            ]
        ],
    ],
]); ?>

<?php \yii\widgets\Pjax::end(); ?>
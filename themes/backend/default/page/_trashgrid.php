<?php
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel common\models\PageSearch */
?>

<?= $this->render('_trashsearch', ['model' => $searchModel]); ?>

<?php \yii\widgets\Pjax::begin(['id' => 'page-trash-grid']); ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '
        <div class="box-header">
            <h3 class="box-title">'. One::t("app", "Item List") .'</h3>
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
        'class' => 'table table-bordered table-striped'
    ],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model) {
                return \yii\helpers\Html::a($model->title, ['update', 'id' => $model->id], [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => One::t('app', 'Click to edit'),
                ]);
            }
        ],
        'slug',
        [
            'attribute' => 'created_by',
            'format' => 'raw',
            'value' => function($model) {
                return $model->getCreatorName();
            }
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
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
            ],
            'singleButtonOptions' => [
                'delete' => [
                    'title' => 'Delete Permanently'
                ]
            ],
        ],
    ],
]); ?>

<?php \yii\widgets\Pjax::end(); ?>
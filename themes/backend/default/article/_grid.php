<?php
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel common\models\ArticleSearch */
?>

<?php \yii\widgets\Pjax::begin(['id' => 'article-grid']); ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => '
        <div class="box-header">
            <h3 class="box-title">'. Yii::t("app", "Article List") .'</h3>
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
            'attribute' => 'post_image',
            'format' => 'html',
            'value' => function($model) {
                return $model->getPostImage([
                    'class' => 'img-responsive',
                    'style' => 'width: 150px'
                ]);
            },
        ],
        [
            'attribute' => 'title',
            'format' => 'html',
            'value' => function ($model) {
                return \yii\helpers\Html::a($model->title, ['update', 'id' => $model->id]);
            }
        ],
        'slug',
        'summary',
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
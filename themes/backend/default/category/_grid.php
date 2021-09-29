<?php
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel common\models\CategorySearch */
?>

<?php \yii\widgets\Pjax::begin(['id' => 'category-grid']); ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'layout' => '
        <div class="box-header">
            <h3 class="box-title">'. Yii::t("app", "Category List") .'</h3>
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
    'afterRow' => function($model, $key, $index, $grid) {
        $childSearchModel = new \common\models\CategorySearch();
        $childSearchModel->parent_id = $model->id;
        $childDataProvider = $childSearchModel->search(One::app()->request->queryParams);

        if ($childDataProvider->getCount() == 0) {
            return null;
        } else {
            return $this->render('_childgrid', [
                'searchModel' => $childSearchModel,
                'dataProvider' => $childDataProvider
            ]);
        }
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function ($model) {
                return \yii\helpers\Html::a($model->name, ['update', 'id' => $model->id]);
            }
        ],
        'slug',
        'description',

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
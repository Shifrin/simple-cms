<?php /* @var $dataProvider \yii\data\ArrayDataProvider */ ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '
        <div class="box-header">
            <h3 class="box-title">'. Yii::t("app", "Layouts List") .'</h3>
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
        [
            'label' => 'Name',
            'value' => function($model) {
                return \yii\helpers\Html::a($model['name'], [
                    'update', 'slug' => $model['slug'], 'type' => $model['type']
                ]);
            },
            'format' => 'raw'
        ],
        [
            'label' => 'Slug',
            'value' => function($model) {
                return $model['slug'];
            }
        ],

        [
            'class' => '\yii\grid\ActionColumn',
            'contentOptions' => [
                'class' => 'actions'
            ],
            'template' => '{delete}',
            'urlCreator' => function($action, $model) {
                $route = [
                    $action, 'slug' => (string) $model['slug'], 'type' => $model['type']
                ];
                return \yii\helpers\Url::toRoute($route);
            }
        ],
    ],
]); ?>
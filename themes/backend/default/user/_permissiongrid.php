<?php
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel common\models\UserSearch */
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<p>
    <?= \yii\helpers\Html::a('<i class="ion-ios-plus-outline"></i> ' . \One::t('app', 'Create New'), ['create-permission'], [
        'class' => 'btn btn-success',
    ]) ?>
</p>

<?php \yii\widgets\Pjax::begin(['id' => 'permission-grid']); ?>

<?= \yii\grid\GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '
        <div class="box-header">
            <h3 class="box-title">'. Yii::t("app", "Permission List") .'</h3>
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

        'name:text',
        'description:text',

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
            'urlCreator' => function($action, $model) {
                switch($action) {
                    case 'update':
                        $action = 'update-permission';
                        break;
                    case 'delete':
                        $action = 'delete-permission';
                        break;
                }

                return \yii\helpers\Url::to([$action, 'name' => $model->name]);
            }
        ],
    ],
]); ?>

<?php \yii\widgets\Pjax::end(); ?>
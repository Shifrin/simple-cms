<?php
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel common\models\CategorySearch */
?>

<tr>
    <td colspan="5" class="no_pad">
        <?php \yii\widgets\Pjax::begin(['id' => 'category-child-grid']); ?>

        <?= \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '
                <div class="box-header">
                    <h3 class="box-title">'. Yii::t("app", "Child List") .'</h3>
                    <div class="box-tools pull-right">{summary}</div>
                </div>
                <div class="box-body table-responsive">{items}</div>
                <div class="box-footer clearfix">{pager}</div>
                <div class="overlay">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            ',
            'showHeader' => false,
            'options' => [
                'class' => 'box box-default no-border grid-view'
            ],
            'summaryOptions' => [
                'class' => 'label label-default summary'
            ],
            'tableOptions' => [
                'class' => 'table'
            ],
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
    </td>
</tr>
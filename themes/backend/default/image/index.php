<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ImageSearch */
/* @var $model common\models\Image */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Images');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="image-index">

    <p>
        <?= Html::a('<i class="ion-ios-upload-outline"></i> ' . Yii::t('app', 'Upload Image'), ['load-form'], [
            'id' => 'open-modal',
            'class' => 'btn btn-success',
            'data-title' => Yii::t('app', 'Upload Image'),
            'data-modal-id' => 'view-modal',
            'data-view-id' => 'image-form',
            'data-view-file' => '_uploadForm',
        ]) ?>
    </p>

    <?php Pjax::begin(['id' => 'image-grid']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '
            <div class="box-header">
                <h3 class="box-title">'. Yii::t("app", "Image List") .'</h3>
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
                'label' => 'Thumbnail',
                'format' => 'html',
                'value' => function($model) {
                    return $model->getThumbnail('default', [
                        'class' => 'img-responsive',
                        'style' => 'width: 150px'
                    ]);
                },
            ],
            'unique_id',
            'title',
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($model) {
                    return $model->getStatus();
                }
            ],

            [
                'class' => 'common\grid\ActionColumn',
                'urlCreator' => function($action, $model) {
                    return Url::to([$action, 'id' => $model->unique_id]);
                },
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

    <?php Pjax::end(); ?>

</div>

<?php
$js = "
    $('body').on('click', 'a.btn-update', function(e) {
        e.preventDefault();

        var Modal = $('#view-modal'),
            title = '" . Yii::t('app', 'Update Information') . "',
            splits = $(this).attr('href').split('='),
            key = splits[splits.length - 1],
            viewId = 'image-form' + key;

        Modal.modal('show');
        Modal.find('.overlay').fadeIn();

        loadView(viewId, {id: key}, '" . Url::to(['load-form']) . "', function(data) {
            Modal.find('.overlay').fadeOut();
            Modal.find('#modal-title').text(title);
            Modal.find('#modal-content').html(data);
        }, function() {
            Modal.find('.overlay').fadeOut();
            Modal.find('#modal-content').html('<p>Form failed to load, please try again!</p>');
        });
    });
";

$this->registerJs($js);

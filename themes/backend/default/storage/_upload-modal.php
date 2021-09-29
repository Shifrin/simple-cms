<?php

/* @var $this yii\web\View */
?>


<?php \yii\bootstrap\Modal::begin([
    'id' => 'upload-modal',
    'size' => \yii\bootstrap\Modal::SIZE_LARGE,
    'header' => One::t('app', 'Upload Files'),
    'footer' => '<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>',
]) ?>

<?= \yii\helpers\Html::beginForm(['upload'], 'post', [
    'encytype' => 'multipart/form-data'
]) ?>

    <?= \kartik\widgets\FileInput::widget([
        'id' => 'file-input',
        'name' => 'file[]',
        'options' => [
            'multiple' => true
        ],
        'pluginOptions' => [
            'uploadUrl' => \yii\helpers\Url::to(['storage/upload']),
            'uploadAsync' => true,
            'maxFileCount' => 10,
            'browseClass' => 'btn btn-success',
            'uploadExtraData' => [
                'path' => One::app()->request->get('path', null)
            ],
        ]
    ]) ?>

<?= \yii\helpers\Html::endForm() ?>

<?php \yii\bootstrap\Modal::end() ?>

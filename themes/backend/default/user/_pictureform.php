<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \common\models\UserProfile */
?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= One::t('app', 'Change Profile Picture') ?></h3>
    </div>
    <div class="box-body">
        <div id="picture" class="center-block" style="max-width: 75%;">
            <?= $model->getPicture(['class' => 'img-responsive img-circle']) ?>
        </div>

        <button class="btn btn-danger btn-delete pull-right" data-toggle="tooltip" data-placement="top" title="Remove Picture">
            <i class="ion-trash-a"></i>
        </button>
    </div>
    <div class="box-footer">
        <?= Html::beginForm(['picture-upload'], 'post', [
            'enctype' => 'multipart/form-data'
        ]); ?>

        <?= \kartik\file\FileInput::widget([
            'id' => 'profile-picture',
            'name' => 'file',
            'pluginOptions' => [
                'uploadUrl' => Url::to(['picture-upload', 'id' => $model->id]),
                'deleteUrl' => Url::to(['picture-delete', 'id' => $model->id]),
                'maxFileCount' => 1,
                'maxFileSize' => 1000,
                'showPreview' => false,
                'browseLabel' => '',
                'uploadLabel' => '',
                'removeLabel' => '',
                'browseClass' => 'btn btn-primary',
                'uploadClass' => 'btn btn-info',
                'removeClass' => 'btn btn-danger',
                'defaultPreviewContent' => $model->getPicture(),
                'allowedFileExtensions' => ['jpg', 'png'],
            ]
        ]) ?>

        <?= Html::endForm(); ?>
    </div>
</div>

<?php
$deleteUrl = Url::to(['picture-delete', 'id' => $model->id]);
$js = <<< JS
    $(function() {
        var picture = $('#picture');
        
        $('#profile-picture').on('fileuploaded', function(event, data, previewId, index) {
            var response = data.response;

            if (response.link) {
                picture.fadeOut('fast');
                picture.find('img').attr('src', response.link);
                picture.fadeIn('slow');
                var n = Noty();
                $.noty.setText(n.options.id, '<i class="fa fa-check-circle"></i> Profile picture successfully uploaded.');
                $.noty.setType(n.options.id, 'success');
            } else {
                var n = Noty();
                $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> ' + response);
                $.noty.setType(n.options.id, 'error');
            }
        });
        
        $('body').on('click', '.btn-delete', function() {
            var deleteConfirm = confirm('Are you sure, you want to remove your picture?');
            
            if (deleteConfirm) {
                $.post('$deleteUrl', '', function(response) {
                    console.log(response);
                    if (response.success) {
                        location.reload();
                    } else {
                        var n = Noty();
                        $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> ' + response.message);
                        $.noty.setType(n.options.id, 'error');
                    }
                });
            }
        });
    });
JS;
$this->registerJs($js);

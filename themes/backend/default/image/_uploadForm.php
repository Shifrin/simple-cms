<?php

/* @var $this yii\web\View */
/* @var $model common\models\Image */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="image-upload">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'id' => 'upload-form',
            'options' => [
                'enctype' => 'multipart/form-data'
            ],
        ]) ?>

        <?= $form->field($model, 'image')->widget(kartik\file\FileInput::className(), [
            'options'=>[
                'accept' => 'image/jpeg',
            ],
            'pluginOptions' => [
                'dropZoneEnabled' => false,
                'uploadUrl' => yii\helpers\Url::to(['upload']),
                'maxFileCount' => 1,
                'browseClass' => 'btn btn-success',
                'browseIcon' => '<i class="ion-android-camera"></i> ',
                'browseLabel' =>  'Select Photo'
            ],
        ]) ?>

        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>

<?php
$js = <<< JS
    $('#image-image').on('fileuploaded', function(event, data, previewId, index) {
        if (data.response.success) {
            $("#view-modal").modal("hide");
            location.reload();
        }
    });
JS;

$this->registerJs($js);

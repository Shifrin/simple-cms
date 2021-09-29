<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

\backend\assets\TinyMCEAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Image */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'image-form',
        'action' => $model->isNewRecord ? ['create'] : ['update', 'id' => $model->unique_id],
    ]);
    ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'category')->widget(\kartik\select2\Select2::className(), [
        'data' => \common\models\Category::allList(),
        'options' => [
            'placeholder' => One::t('app', 'Select Categories'),
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'description')->textarea(['id' => 'editor']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') :
            Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$selected = \yii\helpers\Json::encode(array_keys($model->getCategory()));
$editorCSS = \yii\helpers\Url::to(['/css/editor.css']);
$js = <<< JS
    tinymce.init({
        selector: '#editor',
        menubar: false,
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify outdent indent | bullist numlist | link',
        plugins: 'link',
        resize: false,
        content_css: '$editorCSS',
        height: 300,
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
    
    $('#image-category').val($selected).trigger('change');
    
    $('#image-form').on('beforeSubmit', function(e) {
        var form = $(this),
            message, type;
            
        $('body').addClass('loading');

        $.post(form.attr('action'), form.serialize(), function(response) {
            if (response.success) {
                $("#view-modal").modal("hide");

                if (response.reload) {
                    location.reload();
                } else {
                    $.pjax.reload({container: '#image-grid'});

                    var n = Noty();
                    $.noty.setText(n.options.id, '<i class="fa fa-check-circle"></i> Image updated successfully.');
                    $.noty.setType(n.options.id, 'success');
                }
            } else {
                form.yiiActiveForm('updateMessages', response.messages, false);
            }
        }, 'json').fail(function() {
            var n = Noty();
            $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> Form doesn\'t submit, try again.');
            $.noty.setType(n.options.id, 'error');
        });
    }).on('submit', function(e) {
        e.preventDefault();
    });
    
    $('.modal').on('hide.bs.modal', function(e) {
        tinymce.editors = [];
    });
JS;

$this->registerJs($js);

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

\backend\assets\SpeakingUrlAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="category-form">

        <?php $form = ActiveForm::begin([
            'id' => 'category-form',
            'action' => $model->isNewRecord ? ['create'] : ['update', 'id' => $model->id],
        ]); ?>

        <?= $form->field($model, 'parent_id')->widget(\kartik\select2\Select2::className(), [
            'model' => $model,
            'attribute' => 'parent_id',
            'data' => \common\models\Category::parentList(),
            'options' => [
                'placeholder' => One::t('app', 'Select a Parent'),
            ],
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ]) ?>

        <?= $form->field($model, 'name')->textInput(['id' => 'form-name']) ?>

        <?= $form->field($model, 'slug')->textInput(['id' => 'form-slug']) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                'class' => $model->isNewRecord ? 'btn btn-submit btn-success' : 'btn btn-submit btn-primary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$js = <<< JS
    $(function() {
        function saveForm(form) {
            $.post(form.attr('action'), form.serialize(), function(response) {
                $.each(response.errors, function(attr, messages) {
                    var n = Noty();
                     
                    $.each(messages, function(i, message) {
                        $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> ' + message);
                        $.noty.setType(n.options.id, 'error');
                    });
                        
                    form.yiiActiveForm('updateAttribute', 'category-' + attr, messages);
                });
            });
        }
        
        $('#form-name').blur(function() {
            $('#form-slug').val(getSlug($(this).val()));
        });
        
        $('body').on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $('body').addClass('loading');
            saveForm($('#category-form'));
        });
    });
JS;

$this->registerJs($js);
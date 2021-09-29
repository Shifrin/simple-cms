<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PermissionForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-form">

    <?php $form = ActiveForm::begin([
        'id' => 'permission-form',
        'action' => $model->_isNewRecord ? ['create-permission'] : ['update-permission', 'name' => $model->name],
    ]); ?>

    <?= $form->field($model, 'parent')->widget(\kartik\select2\Select2::className(), [
        'data' => \common\models\AuthItem::permissionList(),
        'options' => [
            'placeholder' => 'Select'
        ]
    ]) ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'rule_name')->widget(\kartik\select2\Select2::className(), [
        'data' => \common\models\AuthItem::ruleList(),
        'options' => [
            'placeholder' => 'Select'
        ]
    ]) ?>

    <?= $form->field($model, 'data')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton($model->_isNewRecord ? One::t('app', 'Create') : One::t('app', 'Update'), [
            'class' => $model->_isNewRecord ? 'btn btn-submit btn-success' : 'btn btn-submit btn-primary'
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
                        
                    form.yiiActiveForm('updateAttribute', 'permissionform-' + attr, messages);
                });
            });
        }
        
        $('body').on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $('body').addClass('loading');
            saveForm($('#permission-form'));
        });
    });
JS;

$this->registerJs($js);
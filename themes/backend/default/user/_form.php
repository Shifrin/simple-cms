<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
        'action' => $model->isNewRecord ? ['create'] : ['update', 'id' => $model->id],
    ]); ?>

    <?= $form->field($model, 'username')->textInput([]) ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'roles')->widget(\kartik\select2\Select2::className(), [
        'data' => \common\models\RoleForm::roleList(),
        'options' => [
            'placeholder' => One::t('app', 'Select'),
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\User::statusList(), [
        'prompt' => 'Select'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? One::t('app', 'Create') : One::t('app', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-submit btn-success' : 'btn btn-submit btn-primary'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$selectedRoles = \yii\helpers\Json::encode(array_keys($model->getRoles()));
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
        
        $('#user-roles').val($selectedRoles).trigger('change');
        
        $('body').on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $('body').addClass('loading');
            saveForm($('#user-form'));
        });
    });
JS;

$this->registerJs($js);
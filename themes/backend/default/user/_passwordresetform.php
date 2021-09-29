<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\PasswordResetForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="password-reset-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'password-reset-form',
    ]);
    ?>

    <fieldset>
        <legend>You can reset your password here</legend>

        <p class="bg-info text-info">The password must have minimum 8 characters long with a combination of
            numbers, lowercase and uppercase characters.</p>

        <?= $form->field($model, 'currentPassword')->passwordInput() ?>

        <?= $form->field($model, 'newPassword')->passwordInput() ?>

    </fieldset>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Change'), [
            'class' => 'btn btn-primary btn-submit'
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
        
        var form = $('#password-reset-form');
        
        $(form).on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $('body').addClass('loading');
            saveForm(form);
        });
    });
JS;

$this->registerJs($js);

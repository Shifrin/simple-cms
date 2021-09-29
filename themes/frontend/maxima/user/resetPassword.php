<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inner-content">
    <div class="container">
        <div class="row shop-login">
            <div class="col-md-6 col-md-offset-3">
                <div class="box-content">
                    <h3 class="uppercase text-center"><?= $this->title ?></h3>

                    <p class="text-center">
                        <?= One::t('app', 'Please choose a new password for your account.') ?>
                    </p>

                    <div class="clearfix space30"></div>

                    <?php $form = \yii\widgets\ActiveForm::begin([
                        'id' => 'password-reset-form',
                        'options' => [
                            'class' => 'logregform',
                        ]
                    ]); ?>

                    <div class="row">
                        <?= $form->field($model, 'password', [
                            'template' => '
                            <div class="form-group">
                                <div class="col-md-12">
                                    {label}{input}
                                </div>
                            </div>
                        '
                        ])->passwordInput(['autofocus' => true]) ?>
                    </div>

                    <div class="clearfix space20"></div>

                    <div class="row">
                        <?= $form->field($model, 'password_repeat', [
                            'template' => '
                            <div class="form-group">
                                <div class="col-md-12">
                                    {label}{input}
                                </div>
                            </div>
                        '
                        ])->passwordInput() ?>
                    </div>

                    <div class="clearfix space20"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= Html::submitButton('Submit', [
                                'class' => 'button btn-md pull-right btn-submit',
                            ]) ?>
                        </div>
                    </div>

                    <?php \yii\widgets\ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<< JS
    $(function () {
        var body = $('body'),
            form = $('#password-reset-form');
        
        body.on('click', '.btn-submit', function(e) {
            e.preventDefault();
            body.addClass('loading');
            
            $.post(form.attr('action'), form.serialize(), function(response) {
                $.each(response.errors, function(attr, messages) {
                    var n = Noty();
                         
                    $.each(messages, function(i, message) {
                        $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> ' + message);
                        $.noty.setType(n.options.id, 'error');
                    });
                            
                    form.yiiActiveForm('updateAttribute', 'loginform-' + attr, messages);
                });
            });
        });
        
        $(document).on('ajaxStop', function () {
            body.removeClass('loading');
        });
    });
JS;

$this->registerJs($js);

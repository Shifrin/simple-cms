<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;

$this->title = One::t('app', 'Login');
$this->params['pageHeader'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inner-content">
    <div class="container">
        <div class="row shop-login">
            <div class="col-md-6 col-md-offset-3">
                <div class="box-content">
                    <h3 class="uppercase text-center"><?= $this->title ?></h3>

                    <div class="clearfix space30"></div>

                    <?php $form = \yii\widgets\ActiveForm::begin([
                        'id' => 'login-form',
                        'options' => [
                            'class' => 'logregform',
                        ]
                    ]); ?>

                    <div class="row">
                        <?= $form->field($model, 'username', [
                            'template' => '
                            <div class="form-group">
                                <div class="col-md-12">
                                    {label}{input}
                                </div>
                            </div>
                        '
                        ])->textInput(['autofocus' => true]) ?>
                    </div>

                    <div class="clearfix space20"></div>

                    <div class="row">
                        <?= $form->field($model, 'password', [
                            'template' => '
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <a class="pull-right" href="' . \yii\helpers\Url::to(['/request-password-reset']) . '">(Lost Password?)</a>
                                        {label}{input}
                                    </div>
                                </div>
                            '
                        ])->passwordInput() ?>
                    </div>

                    <div class="clearfix space20"></div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        </div>

                        <div class="col-md-6">
                            <?= Html::submitButton('Login', [
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
            form = $('#login-form');
        
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

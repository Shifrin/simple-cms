<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<h2 class="login-box-msg"><?= Html::encode($this->title) ?></h2>

<?php
$form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template' => "{input}{error}",
    ],
]);
?>

    <?= $form->field($model, 'username', [
        'inputTemplate' => "{input}<span class=\"ion-person form-control-feedback\"></span>",
        'options' => [
            'class' => 'form-group has-feedback',
            'placeholder' => 'Username/Email'
        ]
    ]) ?>

    <?= $form->field($model, 'password', [
        'inputTemplate' => "{input}<span class=\"ion-android-lock form-control-feedback\"></span>",
        'options' => [
            'class' => 'form-group has-feedback',
            'placeholder' => 'Password'
        ]
    ])->passwordInput() ?>

    <?= $form->field($model, 'rememberMe', [
        'options' => ['class' => 'checkbox icheck']
    ])->checkbox([
        'template' => "<label for=\"loginform-rememberme\">{input} Remember Me</label>"
    ])->label(null) ?>

    <?= Html::submitButton('Login', [
        'class' => 'btn btn-primary btn-block'
    ]) ?>

<?php ActiveForm::end(); ?>

<?php
$js = <<< JS
    $(function () {
        $(document).on('ajaxStart', function () {
            $('body').addClass('loading');
        });
        
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });
    });
JS;

$this->registerJs($js);
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \common\models\UserProfile */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-profile-form">

    <?php
    $form = ActiveForm::begin([
        'id' => 'user-profile-form',
    ]);
    ?>

    <fieldset>
        <legend>Personal</legend>

        <?= $form->field($model, 'first_name')->textInput() ?>

        <?= $form->field($model, 'last_name')->textInput() ?>

        <?= $form->field($model, 'bio_info')->textarea() ?>

        <?= $form->field($model, 'gender')->dropDownList(\common\models\UserProfile::genderList(), [
            'prompt' => 'Select'
        ]) ?>
    </fieldset>

    <fieldset>
        <legend>Social Links</legend>

        <?= $form->field($model, 'website', [
            'inputTemplate' => "<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"fa fa-link\"></i></span>{input}</div>",
        ])->textInput() ?>

        <?= $form->field($model, 'linkedin', [
            'inputTemplate' => "<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"fa fa-linkedin-square\"></i></span>{input}</div>",
        ])->textInput() ?>

        <?= $form->field($model, 'facebook', [
            'inputTemplate' => "<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"fa fa-facebook-square\"></i></span>{input}</div>",
        ])->textInput() ?>

        <?= $form->field($model, 'twitter', [
            'inputTemplate' => "<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"fa fa-twitter\"></i></span>{input}</div>",
        ])->textInput() ?>

        <?= $form->field($model, 'github', [
            'inputTemplate' => "<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"fa fa-github\"></i></span>{input}</div>",
        ])->textInput() ?>
    </fieldset>

    <div class="form-group">
        <?= Html::submitButton(One::t('app', 'Save'), [
            'class' => 'btn btn-primary'
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
        
        var form = $('#user-profile-form');
        
        $(form).on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $('body').addClass('loading');
            saveForm(form);
        });
    });
JS;

$this->registerJs($js);

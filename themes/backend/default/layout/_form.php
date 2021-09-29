<?php
/* @var $this yii\web\View */
/* @var $model \backend\models\Layout */

\backend\assets\AceEditorAsset::register($this);
?>

<div class="layout-form">

    <?php $form = \yii\widgets\ActiveForm::begin([
        'id' => 'layout-form'
    ]) ?>

        <div class="row">
            <div class="col-md-9">
                <?= $form->field($model, 'name')->textInput() ?>
            </div>

            <div class="col-md-3">
                <div class="box box-default">
                    <div class="box-body">
                        <?= \yii\helpers\Html::submitButton('<i class="ion-ios-checkmark-outline"></i> ' . Yii::t('app', 'Save'), [
                            'class' => 'btn btn-success btn-block'
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= \yii\helpers\Html::activeLabel($model, 'content', ['class' => 'control-label']) ?>
            <?= \yii\bootstrap\Html::activeTextarea($model, 'content', [
                'id' => 'textarea-content', 'style' => 'display: none;'
            ]) ?>
            <div id="code-editor" style="width: 100%; height: 350px;"></div>
        </div>

    <?php \yii\widgets\ActiveForm::end() ?>

</div>

<?php
$js = <<< JS
    (function () {
        var textarea = $('#textarea-content');
        var editor = ace.edit('code-editor');
                
        editor.setTheme('ace/theme/github');
        editor.getSession().setMode('ace/mode/php');
        editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true
        });
        editor.blockScrolling= 'Infinity';
        editor.getSession().setValue(textarea.text());
        editor.getSession().on('change', function() {
            var text = editor.getSession().getValue();
            textarea.text(text);
        });
    })();
JS;

$this->registerJs($js);

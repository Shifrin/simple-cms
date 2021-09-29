<?php

use yii\widgets\ActiveForm;

\backend\assets\TinyMCEAsset::register($this);
\backend\assets\DateTimePickerAsset::register($this);
\backend\assets\SpeakingUrlAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Competition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="competition-form">

    <?php $form = ActiveForm::begin([
        'id' => 'competition-form',
    ]); ?>

    <div class="input-buttons">
        <?= \yii\helpers\Html::a('<i class="ion-android-done"></i> ' . One::t('app', 'Save'), ['create'], [
            'class' => 'btn-save', 'role' => 'button'
        ]) ?>
        <?= \yii\helpers\Html::a('<i class="ion-android-open"></i> ' . One::t('app', 'Preview'), One::app()->frontUrlManager->createUrl([
            'home/competition', 'slug' => $model->slug, 'action' => 'preview'
        ]), [
            'class' => 'btn-preview', 'role' => 'button', 'target' => '_blank'
        ]) ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['id' => 'form-title']) ?>

            <?= $form->field($model, 'content')->textarea(['id' => 'editor']) ?>
        </div>

        <div class="col-md-4">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Options') ?></h3>
                </div>

                <div class="box-body">
                    <?= $form->field($model, 'slug', ['template' => '
                        {label}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-link"></i></span>
                            {input}
                        </div>
                        {hint}{error}
                    '])->textInput(['id' => 'form-slug']) ?>

                    <?= $form->field($model, 'status', ['template' => '
                        {label}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-ios-circle-filled"></i></span>
                            {input}
                        </div>
                        {hint}{error}
                    '])->dropDownList(\common\models\Competition::statusList(), [
                        'prompt' => 'Select'
                    ]) ?>

                    <?= $form->field($model, 'publish_at', ['template' => '
                        {label}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-calendar"></i></span>
                            {input}
                        </div>
                        {hint}{error}
                    '])->textInput() ?>

                    <?= $form->field($model, 'author_id', ['template' => '
                        {label}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-person"></i></span>
                            {input}
                        </div>
                        {hint}{error}
                    '])->dropDownList(\common\models\Competition::authorList(), [
                        'prompt' => 'Select'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$editorCSS = \yii\helpers\Url::to(['/css/editor.css']);
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
                        
                    form.yiiActiveForm('updateAttribute', 'page-' + attr, messages);
                });
            });
        }
        
        tinymce.init({
            selector: '#editor',
            menubar: false,
            height: 400,
            toolbar: 'code | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify outdent indent | bullist numlist | link image media',
            plugins: 'code link image media responsivefilemanager',
            resize: false,
            relative_urls: false,
            content_css: '$editorCSS',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            },
            external_filemanager_path: '/filemanager/',
            filemanager_title: 'Responsive Filemanager',
            external_plugins: {
                'filemanager' : '/filemanager/plugin.min.js'
            }
        });
    
        $('#competition-publish_at').datetimepicker();
        
        $('#competition-publish_at').data('DateTimePicker').date('$model->publish_at' == '' ? new Date() :
            new Date('$model->publish_at'));
            
        $('#form-title').blur(function() {
            $('#form-slug').val(getSlug($(this).val()));
        });
        
        $('body').on('click', 'a.btn-preview', function(e) {
            var content = tinymce.get('editor').getContent();
            
            if (content == '') {
                var n = Noty();
                $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> Nothing available to preview.');
                $.noty.setType(n.options.id, 'error');
                e.preventDefault();
            } else {
                $('body').addClass('loading');
                $('a.btn-save').trigger('click');
            }
        });
        
        $('body').on('click', 'a.btn-save', function(e) {
            e.preventDefault();
            $('body').addClass('loading');
            saveForm($('#competition-form'));
        });
    })
JS;

$this->registerJs($js);

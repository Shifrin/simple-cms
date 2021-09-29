<?php

use yii\widgets\ActiveForm;

\backend\assets\TinyMCEAsset::register($this);
\backend\assets\DateTimePickerAsset::register($this);
\backend\assets\SpeakingUrlAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-form">

    <?php $form = ActiveForm::begin([
        'id' => 'page-form',
        'options' => [
            'data-autosave-url' => \yii\helpers\Url::to(['auto-save', 'id' => $model->id]),
        ]
    ]); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="input-buttons">
                <?= \yii\helpers\Html::a('<i class="ion-android-done-all"></i> ' . One::t('app', 'Save'), ['create'], [
                    'class' => 'btn-save', 'role' => 'button'
                ]) ?>
                <?= \yii\helpers\Html::a('<i class="ion-android-open"></i> ' . One::t('app', 'Preview'), One::app()->frontUrlManager->createUrl([
                    $model->slug, 'action' => 'preview'
                ]), [
                    'class' => 'btn-preview', 'role' => 'button', 'target' => '_blank'
                ]) ?>
            </div>

            <?= $form->field($model, 'title')->textInput(['id' => 'form-title']) ?>

            <?= $form->field($model, 'content')->textarea(['id' => 'editor']) ?>
        </div>

        <div class="col-md-4">
            <?php if (!$model->isNewRecord) { ?>
                <div class="box box-default">
                    <div class="box-body">
                        <p>
                            <em>
                                <strong><?= $model->getAttributeLabel('created_by') ?>:</strong> <?= \yii\helpers\Html::encode($model->getCreatorName()) ?>
                            </em>
                        </p>
                        <p>
                            <em>
                                <strong><?= $model->getAttributeLabel('created_at') ?>:</strong> <?= $model->getCreatedAt('medium') ?>
                            </em>
                        </p>
                        <p>
                            <em>
                                <strong><?= $model->getAttributeLabel('updated_by') ?>:</strong> <?= \yii\helpers\Html::encode($model->getUpdaterName()) ?>
                            </em>
                        </p>
                        <em>
                            <strong><?= $model->getAttributeLabel('updated_at') ?>:</strong> <?= $model->getUpdatedAt('medium') ?>
                        </em>
                    </div>
                </div>
            <?php } ?>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Options') ?></h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
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
                    '])->dropDownList(\common\models\Page::statusList(), [
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
                </div>
            </div>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Page Layout') ?></h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?= $form->field($model, 'main_layout', ['template' => '
                        {label}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-th-large"></i></span>
                            {input}
                        </div>
                        {hint}{error}
                    '])->dropDownList(\backend\models\Layout::allList(), [
                        'prompt' => 'Select'
                    ]) ?>

                    <?= $form->field($model, 'partial_layout', ['template' => '
                        {label}
                        <div class="input-group">
                            <span class="input-group-addon"><i class="ion-android-list"></i></span>
                            {input}
                        </div>
                        {hint}{error}
                    '])->dropDownList(\backend\models\LayoutPartial::allList(), [
                        'prompt' => 'Select'
                    ]) ?>
                </div>
            </div>

            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Revisions') ?></h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?php $revisions = $model->getRevisions(); ?>

                    <?php if (!empty($revisions)) { ?>
                        <?php foreach ($revisions as $revision) { ?>
                            <p>
                                <a href="<?= \yii\helpers\Url::to(['update', 'id' => $model->id, 'action' => 'revision_' . $revision->created_at]) ?>">
                                    <?= One::t('app', 'Revision_{datetime}', [
                                        'datetime' => $revision->getCreatedAt('medium')
                                    ]) ?>
                                </a>
                            </p>
                        <?php } ?>
                    <?php } else { ?>
                        <p class="text-muted"><?= One::t('app', 'Not Available.') ?></p>
                    <?php } ?>
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
        
        function autosave(form) {
            var actionUrl = form.data('autosave-url'),
                modelId = '$model->id';
            
            if (modelId === '') {
                return;
            }
            
            $.post(actionUrl, form.serialize());
        }
        
        tinymce.init({
            selector: '#editor',
            menubar: false,
            height: 400,
            toolbar: 'code | undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify outdent indent | bullist numlist | link image media',
            plugins: 'code link image imagetools media responsivefilemanager',
            resize: false,
            content_css: '$editorCSS',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            },
            external_filemanager_path: '/filemanager/',
            filemanager_title: 'Responsive Filemanager',
            external_plugins: {
                filemanager: '/filemanager/plugin.min.js'
            }
        });
        
        setInterval(function() {
            autosave($('#page-form'));
        }, 60000);
    
        $('#page-publish_at').datetimepicker({
            format: 'MM/DD/YYYY hh:mm A'
        });
        
        $('#page-publish_at').data('DateTimePicker').date('$model->publish_at' === '' ? new Date() :
            new Date('$model->publish_at'));
            
        $('#form-title').blur(function() {
            $('#form-slug').val(getSlug($(this).val()));
        });
        
        $('body').on('click', 'a.btn-preview', function(e) {
            e.preventDefault();
            var content = tinymce.get('editor').getContent();
            
            if (content === '') {
                var n = Noty();
                $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> Nothing available in content to preview.');
                $.noty.setType(n.options.id, 'error');
            } else {
                $('body').addClass('loading');
                autosave($('#page-form'));
                $('a.btn-preview').delay(1000).queue(function() {
                    window.open($(this).attr('href'), '_blank');
                });
            }
        });
        
        $('body').on('click', 'a.btn-save', function(e) {
            e.preventDefault();
            $('body').addClass('loading');
            saveForm($('#page-form'));
        });
    });
JS;

$this->registerJs($js);

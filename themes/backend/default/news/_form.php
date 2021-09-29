<?php

use yii\widgets\ActiveForm;

\backend\assets\TinyMCEAsset::register($this);
\backend\assets\DateTimePickerAsset::register($this);
\backend\assets\SpeakingUrlAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<?php \yii\bootstrap\Modal::begin([
    'id' => 'postimage-modal',
    'size' => \yii\bootstrap\Modal::SIZE_LARGE,
    'header' => One::t('app', 'Select Post Image')
]) ?>

<iframe width="870" height="400" src="/filemanager/dialog.php?type=1&field_id=post-image" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>

<?php \yii\bootstrap\Modal::end() ?>

<div class="news-form">
    <?php $form = ActiveForm::begin([
        'id' => 'news-form',
    ]); ?>

    <div class="input-buttons">
        <?= \yii\helpers\Html::a('<i class="ion-android-done"></i> ' . One::t('app', 'Save'), ['create'], [
            'class' => 'btn-save', 'role' => 'button'
        ]) ?>
        <?= \yii\helpers\Html::a('<i class="ion-android-open"></i> ' . One::t('app', 'Preview'), One::app()->frontUrlManager->createUrl([
            'home/article', 'slug' => $model->slug, 'action' => 'preview'
        ]), [
            'class' => 'btn-preview', 'role' => 'button', 'target' => '_blank'
        ]) ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['id' => 'form-title']) ?>

            <?= $form->field($model, 'content')->textarea(['id' => 'editor']) ?>

            <?= $form->field($model, 'summary')->textarea(['id' => 'summary-editor']) ?>
        </div>

        <div class="col-md-4">
            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Options') ?></h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-plus"></i>
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
                    '])->dropDownList(\common\models\News::statusList(), [
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
                    '])->dropDownList(\common\models\News::authorList(), [
                        'prompt' => 'Select'
                    ]) ?>
                </div>
            </div>

            <div class="box box-default collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Category') ?></h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?= \kartik\select2\Select2::widget([
                        'model' => $model,
                        'attribute' => 'category',
                        'data' => \common\models\Category::allList(),
                        'options' => [
                            'placeholder' => One::t('app', 'Select Categories'),
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); ?>
                </div>
            </div>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Post Image') ?></h3>

                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?= \yii\helpers\Html::activeHiddenInput($model, 'post_image', ['id' => 'post-image']) ?>
                    <a href="#postimage-modal" data-toggle="modal">
                        <?= $model->getPostImage(['class' => 'img-responsive post-image']) ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$editorCSS = \yii\helpers\Url::to(['/css/editor.css']);
$isNewRecord = $model->isNewRecord ? 'true' : 'false';
$addCatUrl = \yii\helpers\Url::to(['add-category', 'id' => $model->id]);
$removeCatUrl = \yii\helpers\Url::to(['delete-category', 'id' => $model->id]);
$selectedCategories = \yii\helpers\Json::encode(array_keys($model->getCategory()));
$fileManagerUrl = \yii\helpers\Url::to(['storage/file-manager']);
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
        
        tinymce.init({
            selector: '#summary-editor',
            menubar: false,
            height: 200,
            toolbar: 'undo redo | bold italic',
            resize: true,
            relative_urls: false,
            content_css: '$editorCSS',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            },
        });
    
        $('#news-publish_at').datetimepicker();
        
        $('#news-publish_at').data('DateTimePicker').date('$model->publish_at' == '' ? new Date() :
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
            saveForm($('#news-form'));
        });
        
        $('#news-category').val($selectedCategories).trigger('change');
        
        if ('$isNewRecord' == 'false') {
            $('#news-category').on('select2:selecting', function(e) {
                var val = e.params.args.data.id;
                $.post('$addCatUrl', {'News[category]': [val]});
            }).on('select2:unselect', function(e) {
                var val = e.params.data.id;
                $.post('$removeCatUrl', {'News[category]': [val]});
            });
        }
        
        var postImage = $('#post-image').val();
        
        $('#postimage-modal').on('hidden.bs.modal', function() {
            var imageUrl = $('#post-image').val(),
                imageEl = $('img.post-image');
            
            if (imageUrl != '' && imageUrl != postImage) {
                imageEl.fadeOut('fast', function() {
                    $(this).attr('src', imageUrl);
                    $(this).fadeIn('slow');
                });
            }
        });
    });
JS;

$this->registerJs($js);

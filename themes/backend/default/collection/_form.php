<?php

use yii\widgets\ActiveForm;

\backend\assets\TinyMCEAsset::register($this);
\backend\assets\DateTimePickerAsset::register($this);
\backend\assets\SpeakingUrlAsset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $auction common\models\Auction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-form">

    <?php $form = ActiveForm::begin([
        'id' => 'collection-form',
        'options' => [
            'enctype' => 'multipart/form-data'
        ],
    ]); ?>

    <div class="input-buttons">
        <?= \yii\helpers\Html::a('<i class="ion-android-done"></i> ' . One::t('app', 'Save'), ['create'], [
            'class' => 'btn-save', 'role' => 'button'
        ]) ?>
        <?= \yii\helpers\Html::a('<i class="ion-android-open"></i> ' . One::t('app', 'Preview'), One::app()->frontUrlManager->createUrl([
            'home/collection', 'slug' => $model->slug, 'action' => 'preview'
        ]), [
            'class' => 'btn-preview', 'role' => 'button', 'target' => '_blank'
        ]) ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'title')->textInput(['id' => 'form-title']) ?>

            <?= $form->field($model, 'content')->textarea(['id' => 'editor']) ?>
            
            <?= $form->field($model, 'photos')->widget(\kartik\file\FileInput::className(), [
                'options'=>[
                    'multiple' => true
                ],
                'pluginOptions' => [
                    'uploadUrl' => '',
                    'maxFileCount' => 5,
                    'browseLabel' => 'Select Photos'
                ]
            ]) ?>
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
                    '])->dropDownList(\common\models\Collection::statusList(), [
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
                    '])->dropDownList(\common\models\Collection::authorList(), [
                        'prompt' => 'Select'
                    ]) ?>
                </div>
            </div>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= One::t('app', 'Bid Options') ?></h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <?= $form->field($auction, 'start_price', ['template' => '
                            {label}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-social-usd"></i></span>
                                {input}
                            </div>
                            {hint}{error}
                        '])->textInput() ?>

                        <?= $form->field($auction, 'start_time', ['template' => '
                            {label}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-calendar"></i></span>
                                {input}
                            </div>
                            {hint}{error}
                        '])->textInput() ?>

                        <?= $form->field($auction, 'end_time', ['template' => '
                            {label}
                            <div class="input-group">
                                <span class="input-group-addon"><i class="ion-calendar"></i></span>
                                {input}
                            </div>
                            {hint}{error}
                        '])->textInput() ?>
                    </div>
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
            relative_urls: false,
            resize: false,
            content_css: '$editorCSS',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            },
            fldr: '/collections',
            external_filemanager_path: '/filemanager/',
            filemanager_title: 'Responsive Filemanager',
            external_plugins: {
                'filemanager' : '/filemanager/plugin.min.js'
            }
        });
        
        var now = new Date();
    
        $('#collection-publish_at').datetimepicker();
        $('#auction-start_time').datetimepicker({
            minDate: now
        });
        $('#collection-publish_at').data('DateTimePicker').date('$model->publish_at' == '' ? now :
            new Date('$model->publish_at'));
        $('#auction-start_time').data('DateTimePicker').date('$auction->start_time' == '' ? now :
            new Date('$auction->start_time'));       
        
        now.setDate(now.getDate() + 7);
        
        $('#auction-end_time').datetimepicker({
            minDate: now
        });
        $('#auction-end_time').data('DateTimePicker').date('$auction->end_time' == '' ? now :
            new Date('$auction->end_time'));
            
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
            saveForm($('#collection-form'));
        });
    })
JS;

$this->registerJs($js);

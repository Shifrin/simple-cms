<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CompetitionSearch;

\backend\assets\DateTimePickerAsset::register($this);
$this->registerCssFile(\One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') . '/plugins/select2/select2.min.css', [
    'depends' => [\yii\web\YiiAsset::className()]
]);
$this->registerJsFile(\One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') . '/plugins/select2/select2.full.min.js', [
    'depends' => [\yii\web\YiiAsset::className()]
]);

/* @var $this yii\web\View */
/* @var $model \common\models\CompetitionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="competition-search search-form">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'class' => 'form'
        ]
    ]); ?>

    <?php
    \yii\bootstrap\Modal::begin([
        'header' => '<h4 id="modal-title">' . One::t('app', 'Date Filter') . '</h4>',
        'options' => [
            'id' => 'date-search'
        ],
    ]);
    ?>

    <div id="modal-content">
        <div class="form-group">
            <?= Html::activeDropDownList($model, 'date_attribute', $model->dateAttributes(), [
                'prompt' => '',
                'class' => 'form-control select2',
                'data-placeholder' => One::t('app', 'Select Date Field')
            ]) ?>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group datetimepicker1">
                        <span class="input-group-addon"><i class="ion-calendar"></i></span>
                        <?= Html::activeTextInput($model, 'date_search_from', [
                            'class' => 'form-control',
                            'placeholder' => One::t('app', 'From')
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group datetimepicker2">
                        <span class="input-group-addon"><i class="ion-calendar"></i></span>
                        <?= Html::activeTextInput($model, 'date_search_to', [
                            'class' => 'form-control',
                            'placeholder' => One::t('app', 'To')
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <?= Html::submitButton(One::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php \yii\bootstrap\Modal::end(); ?>

    <div class="row">
        <div class="col-xs-3">
            <?= Html::activeTextInput($model, 'string_search', [
                'class' => 'form-control',
                'placeholder' => One::t('app', 'Search'),
            ]) ?>
        </div>

        <div class="col-xs-3">
            <?= Html::activeDropDownList($model, 'author_id', CompetitionSearch::authorList(), [
                'prompt' => '',
                'class' => 'form-control select2',
                'data-placeholder' => One::t('app', 'Author'),
            ]) ?>
        </div>

        <div class="col-xs-3">
            <?= Html::activeDropDownList($model, 'status', [
                CompetitionSearch::STATUS_PUBLISH => 'Publish',
                CompetitionSearch::STATUS_PENDING => 'Pending',
                CompetitionSearch::STATUS_DRAFT => 'Draft',
            ], [
                'prompt' => '',
                'class' => 'form-control select2',
                'data-placeholder' => One::t('app', 'Status'),
            ]) ?>
        </div>

        <?= Html::button('<i class="ion-calendar"></i>', [
            'class' => 'btn btn-default',
            'data-toggle' => 'modal',
            'data-target' => '#date-search'
        ]) ?>

        <?= Html::submitButton(One::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::button(One::t('app', 'Reset'), ['class' => 'btn btn-primary btn-reset']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$from = $model->date_search_from == null ? '' : One::app()->formatter->asDatetime($model->date_search_from);
$to = $model->date_search_to == null ? '' : One::app()->formatter->asDatetime($model->date_search_to);
$reset = \yii\helpers\Url::to(One::app()->request->get('tab') == 'trash' ? [
    'index', 'tab' => 'trash'] : ['index']);
$js = <<< JS
    $(function() {
        $(".select2").select2({
            allowClear: true,
        });
        
        var fromDate = '$from' == '' ? null : new Date('$from'),
            toDate = '$to' == '' ? null : new Date('$to');
        
        $('.datetimepicker1').datetimepicker({
            date: fromDate,
        });
        $('.datetimepicker2').datetimepicker({
            date: toDate,
            useCurrent: false
        });
        $(".datetimepicker1").on("dp.change", function (e) {
            $('.datetimepicker2').data("DateTimePicker").minDate(e.date);
        });
        $(".datetimepicker2").on("dp.change", function (e) {
            $('.datetimepicker1').data("DateTimePicker").maxDate(e.date);
        });
        
        $('body').on('click', '.btn-reset', function() {
            window.location = '$reset';
        });
    });
JS;
$this->registerJs($js);

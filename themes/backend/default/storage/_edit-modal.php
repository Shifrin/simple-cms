<?php

/* @var $this yii\web\View */
?>

<?php \yii\bootstrap\Modal::begin([
    'id' => 'edit-modal',
    'header' => One::t('app', 'Rename'),
    'footer' => '
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger btn-edit" data-url="' . \yii\helpers\Url::to(['storage/rename']) . '">Yes</button>
    ',
]) ?>

<?= \yii\helpers\Html::beginForm(['rename']); ?>

    <div class="form-group">
        <?= \yii\helpers\Html::input('text', 'file-name', '', [
            'class' => 'form-control', 'id' => 'file-name'
        ]) ?>
    </div>

<?= \yii\helpers\Html::endForm(); ?>

<?php \yii\bootstrap\Modal::end() ?>

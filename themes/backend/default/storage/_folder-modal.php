<?php

/* @var $this yii\web\View */
?>

<?php \yii\bootstrap\Modal::begin([
    'id' => 'folder-modal',
    'header' => 'Folder Name',
    'footer' => '
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success btn-create">Create</button>
    ',
]) ?>

<?= \yii\helpers\Html::beginForm(['new-folder']); ?>

    <div class="form-group">
        <?= \yii\helpers\Html::input('text', 'folder', '', [
            'class' => 'form-control', 'id' => 'new-folder'
        ]) ?>
    </div>

<?= \yii\helpers\Html::endForm(); ?>

<?php \yii\bootstrap\Modal::end() ?>

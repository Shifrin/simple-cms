<?php

/* @var $this yii\web\View */
?>

<?php \yii\bootstrap\Modal::begin([
    'id' => 'delete-modal',
    'header' => One::t('app', 'Confirm Deletion'),
    'footer' => '
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger btn-delete" data-url="' . \yii\helpers\Url::to(['storage/delete']) . '">Yes</button>
    ',
]) ?>

<?= One::t('app', 'Are you sure, you want to delete selected files?') ?>

<?php \yii\bootstrap\Modal::end() ?>

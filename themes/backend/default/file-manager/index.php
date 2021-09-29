<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'File Manager');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="file-manager-index">
    <div class="box box-primary">
        <div class="box-body">
            <iframe width="100%" height="500" src="/filemanager/dialog.php?type=0" frameborder="0" style="overflow: scroll; overflow-x: hidden; overflow-y: scroll; "></iframe>
        </div>
    </div>
</div>
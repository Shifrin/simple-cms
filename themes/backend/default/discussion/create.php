<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Create New Discussion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Discussions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discussion-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

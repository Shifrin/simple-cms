<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Create New Competition');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Competition'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="competition-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

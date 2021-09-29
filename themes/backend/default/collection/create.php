<?php
/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Create New Collection');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Collection'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-create">

    <?= $this->render('_form', [
        'model' => $model,
        'auction' => $auction,
    ]) ?>

</div>

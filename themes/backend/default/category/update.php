<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Update Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>

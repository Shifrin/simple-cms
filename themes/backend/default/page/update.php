<?php

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $revision common\models\Revision */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Page',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="page-update">

    <?php if ($model->getLastAutosaved() !== null && One::app()->request->get('action') == null) { ?>
        <div class="callout callout-info">
            <p>
                <a href="<?= \yii\helpers\Url::to(['update', 'id' => $model->id, 'action' => 'autosave']) ?>">
                    <?= One::t('app', '{icon} Load from last auto saved.', [
                        'icon' => '<i class="glyphicon glyphicon-floppy-saved"></i>'
                    ]) ?>
                </a>
            </p>
        </div>
    <?php } ?>

    <?php if (One::app()->request->get('action') == 'autosave') { ?>
        <div class="callout callout-success">
            <p>
                <?= One::t('app', '{icon} Loaded from last auto saved.', [
                    'icon' => '<i class="glyphicon glyphicon-floppy-saved"></i>'
                ]) ?>
            </p>
        </div>
    <?php } ?>

    <?php if ($revision !== null && $revision->type === \common\models\Revision::TYPE_DEFAULT) { ?>
        <div class="callout callout-success">
            <p>
                <?= One::t('app', '{icon} Loaded from "Revision_{datetime}".', [
                    'icon' => '<i class="ion-compose"></i>',
                    'datetime' => $revision->getCreatedAt('medium')
                ]) ?>
            </p>
        </div>
    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

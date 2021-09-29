<?php
/* @var $this yii\web\View */

$this->title = \One::t('app', 'Layouts');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="layout-index">
    <div class="">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#tab-main" data-toggle="tab" role="tab">Main</a>
            </li>
            <li>
                <a href="#tab-partial" data-toggle="tab" role="tab">Partial</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab-main" role="tabpanel">
                <p>
                    <?= \yii\helpers\Html::a('<i class="ion-ios-plus-outline"></i> ' . \One::t('app', 'Create'), ['create'], [
                        'class' => 'btn btn-success',
                    ]) ?>
                </p>

                <?= $this->render('_grid', ['dataProvider' => $mainDataProvider]) ?>
            </div>
            <div class="tab-pane fade" id="tab-partial" role="tabpanel">
                <p>
                    <?= \yii\helpers\Html::a('<i class="ion-ios-plus-outline"></i> ' . \One::t('app', 'Create'), ['create', 'type' => 'partial'], [
                        'class' => 'btn btn-success',
                    ]) ?>
                </p>

                <?= $this->render('_grid', ['dataProvider' => $partialDataProvider]) ?>
            </div>
        </div>
    </div>
</div>

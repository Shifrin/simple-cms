<?php
/* @var $this \yii\web\View */
/* @var $model \common\models\Image */
/* @var $relatedModels \common\models\Image[] */

$this->title = $model->getTitle() == null ? "#{$model->unique_id}" :
    "#{$model->unique_id} - " . $model->getTitle();
$this->params['pageHeader'] = One::t('app', '{span}{title}', [
    'span' => '<span>Photos</span>',
    'title' => $this->title,
]);
$this->params['breadcrumbs'][] = ['label' => One::t('app', 'Photos'), 'url' => ['/photos']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- INNER CONTENT -->
<div class="shop-content">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1" id="p-single">
                <div class="product-single">
                    <div class="product-thumbnail">
                        <a class="mp-lightbox" href="<?= $model->getThumbnailSource('large') ?>">
                            <?= $model->getThumbnail('medium', ['class' => 'img-responsive']) ?>
                        </a>
                    </div>

                    <div class="space40"></div>

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="button btn-desc btn-full btn-radius btn-border">
                                        <?= One::t('app', '{icon} Downloads: {counts}', [
                                            'icon' => '<i class="fa fa-download"></i>',
                                            'counts' => $model->getDownloadCounts()
                                        ]) ?>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <?= \yii\helpers\Html::a('<i class="fa fa-download"></i>' . One::t('app', 'Free Download'), [
                                        'download', 'id' => $model->unique_id
                                    ], [
                                        'class' => 'button btn-desc btn-full btn-border btn-reveal btn-radius color3',
                                        'data-method' => 'post'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space40"></div>

                <div class="tab-style3">
                    <!-- Nav Tabs -->
                    <div class="align-center mb-40 mb-xs-30">
                        <ul class="nav nav-tabs tpl-minimal-tabs animate">
                            <li class="col-md-4 active">
                                <a aria-expanded="true" href="#mini-one" data-toggle="tab">Photo Info</a>
                            </li>
                            <li class="col-md-4">
                                <a aria-expanded="false" href="#mini-two" data-toggle="tab">File Info</a>
                            </li>
                            <li class="col-md-4">
                                <a aria-expanded="false" href="#mini-three" data-toggle="tab">Camera Info</a>
                            </li>
                        </ul>
                    </div>
                    <!-- End Nav Tabs -->
                    <!-- Tab panes -->
                    <div class="tab-content tpl-minimal-tabs-cont align-center section-text">
                        <div class="tab-pane fade active in" id="mini-one">
                            <h2><?= $model->getTitle() ?></h2>
                            <?= $model->description ?>
                        </div>

                        <div class="tab-pane fade" id="mini-two">
                            <table class="table tba2">
                                <tbody>
                                    <?php foreach ($model->getImageInfo() as $key => $value) { ?>
                                        <?php if ($key == 'File Name') continue; ?>
                                        <tr>
                                            <td><?= One::t('app', '{key}', ['key' => $key]) ?></td>
                                            <td><?= $value ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="mini-three">
                            <table class="table tba2">
                                <tbody>
                                <?php foreach ($model->getCameraInfo() as $key => $value) { ?>
                                    <tr>
                                        <td><?= One::t('app', '{key}', ['key' => $key]) ?></td>
                                        <td><?= $value ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space60"></div>

        <div class="related-products">
            <h4 class="uppercase"><?= One::t('app', 'Related Photos') ?></h4>

            <hr>

            <div class="row">
                <ul class="shop-grid">
                    <?php foreach ($relatedModels as $relatedModel) { ?>
                        <li>
                            <div class="product">
                                <span class="badge download-count">
                                    <i class="fa fa-download"></i> <?= $relatedModel->getDownloadCounts() ?>
                                </span>

                                <div class="text-center">
                                    <div class="product-thumbnail">
                                        <?= $relatedModel->getThumbnail('default', [
                                            'alt' => $relatedModel->title, 'class' => 'img-responsive'
                                        ]) ?>
                                    </div>
                                    <h3 class="product-title">
                                        <a href="<?= \yii\helpers\Url::to(['photo/index', 'id' => $relatedModel->unique_id]) ?>">
                                            <?= \yii\helpers\Html::encode($relatedModel->getTitle(27)) ?>
                                        </a>
                                    </h3>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
$js = <<< JS
    $(function() {
        $('.mp-lightbox').magnificPopup({
            removalDelay: 300,
            type: 'image',
            closeOnContentClick: true,
            mainClass: 'mfp-fade',
            image: {
                verticalFit: false
            },
            gallery:{
                enabled:true
            }
        });
    });
JS;
$this->registerJs($js);


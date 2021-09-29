<?php

/* @var $this yii\web\View */

$this->title = One::app()->name;
$this->params['pageHeader'] = One::t('app', 'Welcome to Arab Photo Store');
?>

<div class="inner-content">
    <div class="container">
        <div class="photos">
            <div class="text-center space30">
                <h2 class="title uppercase"><?= One::t('app', 'Latest Photos') ?></h2>
            </div>

            <div class="row">
                <ul class="shop-grid">
                    <?php foreach ($images as $image) { ?>
                        <?php /* @var $image \common\models\Image*/ ?>
                        <li>
                            <div class="product">
                                <span class="badge download-count">
                                    <i class="fa fa-download"></i> <?= $image->getDownloadCounts() ?>
                                </span>

                                <div class="text-center">
                                    <div class="product-thumbnail">
                                        <a href="<?= \yii\helpers\Url::to(['photo/index', 'id' => $image->unique_id]) ?>">
                                            <?= $image->getThumbnail('default', [
                                                'alt' => $image->title, 'class' => 'img-responsive'
                                            ]) ?>
                                        </a>
                                        <!--                                <div class="product-overlay">-->
                                        <!--                                    <a class="product-overlay-link" href="#"><i class="fa fa-search-plus"></i></a>-->
                                        <!--                                    <a class="product-overlay-cart" href="#"><i class="fa fa-cart-plus"></i></a>-->
                                        <!--                                </div>-->
                                    </div>
                                    <h3 class="product-title">
                                        <a href="<?= \yii\helpers\Url::to(['photo/index', 'id' => $image->unique_id]) ?>">
                                            <?= \yii\helpers\Html::encode($image->getTitle(27)) ?>
                                        </a>
                                    </h3>
                                    <!--                            <span class="product-price">£ 69.99</span>-->
                                    <!--                            <a href="#" class="button btn-md btn-radius btn-center color2 btn-radius add_to_cart_button">Add to cart</a>-->
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="space20"></div>

            <a href="<?= \yii\helpers\Url::to(['photos']) ?>" class="button btn-center btn-border btn-lg">
                Explore More
            </a>

            <div class="space60"></div>
        </div>
    </div>
</div>
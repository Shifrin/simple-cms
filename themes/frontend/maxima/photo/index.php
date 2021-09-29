<?php
/* @var $this \yii\web\View */
/* @var $model \common\models\Image */

$this->title = One::t('app', 'Photos');
$this->params['pageHeader'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- INNER CONTENT -->
<div class="inner-content">
    <div class="container">
        <div class="row">
            <ul class="shop-grid">
                <?php foreach ($models as $model) { ?>
                    <li>
                        <div class="product">
                            <span class="badge download-count">
                                <i class="fa fa-download"></i> <?= $model->getDownloadCounts() ?>
                            </span>

                            <div class="text-center">
                                <div class="product-thumbnail">
                                    <a href="<?= \yii\helpers\Url::to(['photo/index', 'id' => $model->unique_id]) ?>">
                                        <?= $model->getThumbnail('default', [
                                            'alt' => $model->title, 'class' => 'img-responsive'
                                        ]) ?>
                                    </a>
                                </div>
                                <h3 class="product-title">
                                    <a href="<?= \yii\helpers\Url::to(['photo/index', 'id' => $model->unique_id]) ?>">
                                        <?= \yii\helpers\Html::encode($model->getTitle(27)) ?>
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

        <!-- Pagination -->
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
            'options' => [
                'class' => 'page_nav',
            ],
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
            'registerLinkTags' => true,
            'maxButtonCount' => 5
        ]); ?>
    </div>
</div>
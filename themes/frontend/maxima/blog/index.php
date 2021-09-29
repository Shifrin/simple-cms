<?php
/* @var $this \yii\web\View */
/* @var $model \common\models\Article */

$this->title = One::t('app', 'Blog');
$this->params['pageHeader'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- INNER CONTENT -->
<div class="inner-content">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 blog-content">
                <?php if (!empty($models)) : ?>
                    <?php foreach ($models as $key => $model) { ?>
                        <article class="blogpost">
                            <h2 class="post-title">
                                <a href="<?= \yii\helpers\Url::to(['blog/index', 'slug' => $model->slug]) ?>">
                                    <?= $model->getTitle() ?>
                                </a>
                            </h2>

                            <div class="post-meta">
                                <span><i class="ion-clock"></i> <?= $model->getPublishAt('medium') ?></span>
                                <span><a href="#"><i class="ion-person"></i> <?= $model->getAuthorName() ?></a></span>
                            </div>

                            <div class="space20"></div>

                            <div class="post-media">
                                <?= $model->getPostImage(['class' => 'img-responsive']) ?>
                            </div>

                            <div class="space20"></div>

                            <div class="post-excerpt">
                                <p><?= $model->getSummary(); ?></p>
                            </div>

                            <a href="<?= \yii\helpers\Url::to(['blog/index', 'slug' => $model->slug]) ?>" class="button btn-xs">
                                Read More&nbsp;&nbsp;<i class="fa fa-angle-right"></i>
                            </a>
                        </article>

                        <?php if (count($models) != ($key + 1)) { ?>
                            <div class="blog-sep"></div>
                        <?php } ?>
                    <?php } ?>
                <?php else : ?>
                    <p class="lead">No articles found!</p>
                <?php endif; ?>

                <div class="space70"></div>

                <!-- Pagination -->
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                    'options' => [
                        'class' => 'page_nav',
                    ],
                    'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
                    'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
                    'registerLinkTags' => true
                ]); ?>
            </div>
        </div>
    </div>
</div>
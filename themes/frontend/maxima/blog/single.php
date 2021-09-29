<?php
/* @var $this \yii\web\View */
/* @var $model \common\models\News */

$this->title = $model->getTitle();
$this->params['pageHeader'] = One::t('app', '{span}{title}', [
    'span' => '<span>Blog</span>',
    'title' => $model->getTitle(),
]);
$this->params['breadcrumbs'][] = ['label' => One::t('app', 'News'), 'url' => ['blog/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- INNER CONTENT -->
<div class="inner-content">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="blog-single">
                    <article class="blogpost">
                        <h2 class="post-title">
                            <?= $model->getTitle() ?>
                        </h2>

                        <div class="post-meta">
                            <span><i class="ion-clock"></i> <?= $model->getPublishAt('medium') ?></span>
                            <span><a href="#"><i class="ion-person"></i> <?= $model->getAuthorName() ?></a></span>
                        </div>

                        <div class="space30"></div>

                        <div class="post-media">
                            <?= $model->getPostImage(['class' => 'img-responsive']) ?>
                        </div>

                        <div class="space30"></div>

                        <?= $model->content ?>
                    </article>
                </div>
            </div>
        </div>
    </div>
</div>

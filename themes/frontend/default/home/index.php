<?php

/* @var $this yii\web\View */

$this->title = One::app()->name;
?>

<div class="search-box">
    <div class="container-fluid">
        <form class="search-form">
            <div class="input-group">
                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                <input type="text" class="form-control input-lg" placeholder="Search..." aria-label="search">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Photos <span class="glyphicon glyphicon-chevron-down"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#">Photos</a></li>
                        <li><a href="#">News</a></li>
                        <li><a href="#">Articles</a></li>
                    </ul>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="most-categories section">
    <div class="container">
        <header class="section-header text-center">
            <h2 class="section-title">
                <?= One::t('app', 'Explore millions of photos from the most browsed categories') ?>
            </h2>
        </header>

        <div class="section-content">
            <div class="row">
                <?php foreach ($mostCategories as $category) { ?>
                    <div class="col-xs-6 col-sm-6 col-md-4">
                        <a href="<?= One::app()->urlManager->createUrl(['category/slug', 'slug' => $category->slug]) ?>" class="thumbnail">
                            <?= $category->getThumbnail('default', [
                                'alt' => $category->name, 'class' => 'img-responsive'
                            ]) ?>
                            <div class="caption">
                                <h3><?= \yii\helpers\Html::encode($category->name) ?></h3>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<div class="posts section">
    <div class="container">
        <div class="section-content">
            <div class="row">
                <div class="col-md-6">
                    <?php for ($i = 1; $i <= 3; $i++) { ?>
                        <div class="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="post-image">
                                        <img src="<?= \yii\helpers\Url::to(['images/no-thumb.jpg']) ?>" alt="" class="img-responsive">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="post-content">
                                        <h3 class="post-title">News <?= $i ?></h3>
                                        <p>Aenean ut eros et nisl sagittis vestibulum. Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Nulla sit amet est. Fusce a quam.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="col-md-6">
                    <?php for ($i = 1; $i <= 3; $i++) { ?>
                        <div class="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="post-image">
                                        <img src="<?= \yii\helpers\Url::to(['images/no-thumb.jpg']) ?>" alt="" class="img-responsive">
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="post-content">
                                        <h3 class="post-title">Article <?= $i ?></h3>
                                        <p>Aenean ut eros et nisl sagittis vestibulum. Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Nulla sit amet est. Fusce a quam.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
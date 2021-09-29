<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
\frontend\assets\IconAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= One::app()->language ?>">
    <head>
        <meta charset="<?= One::app()->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <header class="main-header">
            <div class="navbar navbar-inverse">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <a class="navbar-brand" href="<?= One::app()->homeUrl ?>">
                            <img alt="Brand" src="<?= \yii\helpers\Url::to(['/images/logo.png']) ?>">
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="navbar-main">
                        <?php
                        $items = [
                            [
                                'label' => One::t('app', 'Login'),
                                'url' => ['/login'],
                                'visible' => One::app()->user->isGuest
                            ],
                            [
                                'label' => One::t('app', 'Sign Up'),
                                'url' => ['/login'],
                                'visible' => One::app()->user->isGuest
                            ],
                        ];

                        if (!One::app()->user->isGuest) {
                            $items[] = [
                                'label' => '<i class="ion-android-person"></i> ' . One::app()->user->identity->name,
                                'options' => ['class' => 'user'],
                                'encode' => false,
                                'items' => [
                                    [
                                        'label' => One::t('app', 'Dashboard'),
                                        'url' => One::app()->backUrlManager->createUrl(['dashboard']),
                                    ],
                                    [
                                        'label' => One::t('app', 'Log out'),
                                        'url' => ['/logout'],
                                        'linkOptions' => [
                                            'data-method' => 'post'
                                        ]
                                    ],
                                ]
                            ];
                        }
                        ?>
                        <?= Nav::widget([
                            'options' => [
                                'class' => 'nav navbar-nav navbar-right'
                            ],
                            'items' => $items
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-sub" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="navbar-sub">
                        <?= Nav::widget([
                            'options' => [
                                'class' => 'nav navbar-nav'
                            ],
                            'items' => [
                                [
                                    'label' => One::t('app', 'Photos'),
                                    'url' => ['photo/index'],
                                ],
                                '<li class="vertical-divider"></li>',
                                [
                                    'label' => One::t('app', 'News'),
                                    'url' => ['news/index'],
                                ],
                                '<li class="vertical-divider"></li>',
                                [
                                    'label' => One::t('app', 'Articles'),
                                    'url' => ['article/index'],
                                ],
                                '<li class="vertical-divider"></li>',
                                [
                                    'label' => One::t('app', 'Discussions'),
                                    'url' => ['discussion/index'],
                                ],
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </header>

        <div class="main-content">
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= One::app()->name ?> <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
</html>

<?php $this->endPage() ?>

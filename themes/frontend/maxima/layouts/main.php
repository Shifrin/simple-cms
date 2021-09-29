<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use themes\frontend\maxima\assets\ThemeAsset;

ThemeAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= One::app()->language ?>">
    <head>
        <meta http-equiv="content-type" content="text/html">
        <meta charset="<?= One::app()->charset ?>">
        <title><?= Html::encode($this->title) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="">
        <?= Html::csrfMetaTags() ?>

        <?php $this->registerCssFile('/css/custom.css', [
            'depends' => [ThemeAsset::className()]
        ]) ?>

        <?php $this->head() ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.2/jquery.xdomainrequest.min.js"></script>
        <![endif]-->
    </head>

    <body class="<?= One::app()->controller->id . '-' . One::app()->controller->action->id ?>">
        <?php $this->beginBody() ?>

        <div class="outer-wrapper" id="page-top">
            <div class="header-wrap">
                <!-- HEADER -->
                <header id="header-main">
                    <div class="container">
                        <div class="navbar yamm navbar-default">
                            <div class="navbar-header">
                                <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a href="<?= Url::home(['/']) ?>" class="navbar-brand">
                                    <img src="<?= Url::to(['/images/logo.png']) ?>" alt="<?= One::app()->name ?>">
                                </a>
                            </div>

                            <!-- SEARCH -->
                            <div class="header-x pull-right">
                                <div class="s-search">
                                    <div class="ss-trigger"><i class="ion-ios-search-strong"></i></div>
                                    <div class="ss-content">
                                        <span class="ss-close ion-close"></span>
                                        <div class="ssc-inner">
                                            <form>
                                                <input type="text" placeholder="Type Search text here...">
                                                <button type="submit"><i class="ion-ios-search-strong"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="navbar-collapse-1" class="navbar-collapse collapse navbar-right">
                                <?php
                                $items = [
                                    [
                                        'label' => One::t('app', 'Home'),
                                        'url' => ['/'],
                                    ],
                                    [
                                        'label' => One::t('app', 'Photos'),
                                        'url' => ['photo/index'],
                                    ],
                                    [
                                        'label' => One::t('app', 'Blog'),
                                        'url' => ['blog/index'],
                                    ],
                                    [
                                        'label' => One::t('app', '<i class="ion-log-in"></i> Login'),
                                        'url' => ['user/login'],
                                        'visible' => One::app()->user->isGuest,
                                        'encode' => false
                                    ],
                                    [
                                        'label' => One::t('app', '<i class="ion-android-person-add"></i> Sign Up'),
                                        'url' => ['user/signup'],
                                        'visible' => One::app()->user->isGuest,
                                        'encode' => false
                                    ],
                                ];

                                if (!One::app()->user->isGuest) {
                                    $items[] = [
                                        'label' => '<i class="ion-android-person"></i> ' . One::app()->user->identity->getName(),
                                        'encode' => false,
                                        'items' => [
                                            [
                                                'label' => One::t('app', '<i class="ion-speedometer"></i> Dashboard'),
                                                'url' => One::app()->backUrlManager->createUrl(['dashboard']),
                                                'encode' => false,
                                            ],
                                            [
                                                'label' => One::t('app', '<i class="ion-compose"></i> Profile'),
                                                'url' => One::app()->backUrlManager->createUrl(['user/profile', 'id' => One::app()->user->id]),
                                                'encode' => false,
                                            ],
                                            [
                                                'label' => One::t('app', '<i class="ion-log-out"></i> Log out'),
                                                'url' => ['user/logout'],
                                                'linkOptions' => [
                                                    'data-method' => 'post'
                                                ],
                                                'encode' => false,
                                            ],
                                        ]
                                    ];
                                }
                                ?>

                                <?= Nav::widget([
                                    'options' => [
                                        'class' => 'nav navbar-nav'
                                    ],
                                    'items' => $items,
                                    'dropDownCaret' => Html::tag('div', '<i class="fa fa-angle-down"></i>', [
                                        'class' => 'arrow-up'
                                    ])
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </header>
            </div>

            <?php if (One::app()->controller->action->id !== 'maintenance') { ?>
                <!-- PAGE HEADER -->
                <div class="page_header">
                    <div class="page_header_parallax">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="text-center">
                                        <?= isset($this->params['pageHeader']) ? $this->params['pageHeader'] : '' ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($this->params['breadcrumbs'])) { ?>
                        <div class="bcrumb-wrap">
                            <div class="container">
                                <?= \yii\widgets\Breadcrumbs::widget([
                                    'homeLink' => [
                                        'label' => '<i class="fa fa-home"></i> Home',
                                        'url' => ['home/index'],
                                        'template' => "<li>{link}</li>\n"
                                    ],
                                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                    'encodeLabels' => false,
                                    'itemTemplate' => "<li>{link}</li>",
                                    'options' => [
                                        'class' => 'bcrumbs'
                                    ]
                                ]) ?>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <?= $content ?>
        </div>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="space30">About us</h4>
                        <p>Lorem ipsum dolor sit amet consec tetur elit vel quam ligula. Duis vel pulvinar diam in lacus non nisl commodo convallis.</p>
                        <p>Phasellus rutrum urna ut nibh congue, ut vehicula nibh ultricies.</p>
                    </div>

                    <div class="col-md-6">
                        <h4 class="space30">Contact</h4>

                        <ul class="c-info">
                            <li><i class="fa fa-phone"></i> (+974) 1234 5678</li>
                            <li><i class="fa fa-envelope-o"></i> support@arabphotostore.com</li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

        <div class="footer-bottom">
            <div class="container">
                <p>
                    &copy; Copyright <?= date('Y') ?>. <a href="<?= Url::home(['/']) ?>" target="_blank"><?= One::app()->name ?></a>
                </p>
            </div>
        </div>

        <div class="overlay-wrapper">
            <div class="overlay">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
            </div>
        </div>

        <?php
        \shifrin\noty\NotyWidget::widget([
            'options' => [
                'dismissQueue' => true,
                'layout' => 'topCenter',
                'theme' => 'relax',
                'animation' => [
                    'open' => 'animated flipInX',
                    'close' => 'animated flipOutX',
                ],
                'timeout' => false
            ],
            'registerFontAwesomeCss' => false,
            'registerButtonsCss' => false
        ]);
        ?>

        <?php $this->endBody() ?>
    </body>
</html>

<?php $this->endPage() ?>

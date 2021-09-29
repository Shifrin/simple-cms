<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\widgets\SidebarNav;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\Modal;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= \One::app()->language ?>">
    <head>
        <meta charset="<?= \One::app()->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode(!empty($this->title) ? $this->title . ' - ' . \One::app()->name : $this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="hold-transition skin-blue fixed sidebar-mini">
        <?php $this->beginBody() ?>

        <?php
        Modal::begin([
            'header' => '<h4 id="modal-title"></h4>',
            'options' => [
                'id' => 'view-modal'
            ],
            'size' => 'modal-lg'
        ]);
        ?>

        <div id="modal-content"></div>

        <div class="overlay-wrapper">
            <div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
        </div>

        <?php Modal::end(); ?>

        <div class="wrapper">
            <header class="main-header">
                <a href="<?= \One::app()->homeUrl ?>" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>SCMS</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Simple</b>CMS</span>
                </a>

                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <?php if (!\One::app()->user->isGuest) { ?>
                        <div class="navbar-custom-menu">
                            <ul class="nav navbar-nav">
                                <li class="dropdown user user-menu">
                                    <!-- Menu Toggle Button -->
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                        <!-- The user image in the navbar-->
                                        <?= \One::app()->user->identity->profile->getPicture(['class' => 'user-image']) ?>
                                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                        <span class="hidden-xs"><?= \One::app()->user->identity->name ?></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <!-- The user image in the menu -->
                                        <li class="user-header">
                                            <?= \One::app()->user->identity->profile->getPicture(['class' => 'img-circle']) ?>

                                            <p>
                                                <?= One::app()->user->identity->name ?>
                                                <small>Last logged in on: <?= \One::app()->formatter->asDate(\One::app()->user->identity->last_logged_in_time, 'dd-MM-yyyy hh:mm a') ?></small>
                                            </p>
                                        </li>
                                        <!-- Menu Footer-->
                                        <li class="user-footer">
                                            <div class="pull-left">
                                                <a href="<?= Url::to(['/user/profile', 'id' => \One::app()->user->id]) ?>" class="btn btn-default btn-flat">
                                                    <i class="ion-person"></i> Profile
                                                </a>
                                            </div>
                                            <div class="pull-right">
                                                <a href="<?= Url::to(['/home/logout']) ?>" data-method="post" class="btn btn-default btn-flat">
                                                    <i class="ion-log-out"></i> Log out
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                </nav>
            </header>

            <aside class="main-sidebar">
                <section class="sidebar">
                    <?php if (!\One::app()->user->isGuest) { ?>
                        <!-- Sidebar user panel -->
                        <div class="user-panel">
                            <div class="pull-left image">
                                <?= \One::app()->user->identity->profile->getPicture(['class' => 'img-circle']) ?>
                            </div>
                            <div class="pull-left info">
                                <p><?= \One::app()->user->identity->name ?></p>
                                <!-- Status -->
                                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                            </div>
                        </div>
                        <!-- Sidebar Menu -->
                    <?php } ?>

                    <?= SidebarNav::widget([
                        'items' => [
                            [
                                'label' => '<i class="fa fa-home"></i> <span>Website</span>',
                                'url' => \One::app()->frontUrlManager->createUrl(['/']),
                                'linkOptions' => [
                                    'class' => 'text-aqua',
                                    'target' => '_blank'
                                ],
                            ],
                            '<li class="header">ADMINISTRATION</li>',
                            [
                                'label' => '<i class="fa fa-dashboard"></i> <span>Dashboard</span>',
                                'url' => ['/dashboard'],
                                'active' => \One::app()->controller->id == 'dashboard',
                                'visible' => \One::app()->user->can('Dashboard')
                            ],
                            [
                                'label' => '<i class="fa fa-th-large"></i> <span>Layouts</span>',
                                'url' => ['/layout'],
                                'active' => \One::app()->controller->id == 'layout',
                                'visible' => \One::app()->user->can('Layout')
                            ],
                            [
                                'label' => '<i class="fa fa-newspaper-o"></i> <span>Pages</span>',
                                'url' => ['/page'],
                                'active' => \One::app()->controller->id == 'page',
                                'visible' => \One::app()->user->can('Page')
                            ],
                            [
                                'label' => '<i class="fa fa-bookmark"></i> <span>Categories</span>',
                                'url' => ['/category'],
                                'active' => \One::app()->controller->id == 'category',
                                'visible' => \One::app()->user->can('Category')
                            ],
                            [
                                'label' => '<i class="fa fa-list-alt"></i> <span>Articles</span>',
                                'url' => ['/article'],
                                'active' => \One::app()->controller->id == 'article',
                                'visible' => \One::app()->user->can('Article')
                            ],
                            [
                                'label' => '<i class="fa fa-user"></i> <span>Users</span>',
                                'url' => ['/user'],
                                'active' => \One::app()->controller->id == 'user',
                                'visible' => \One::app()->user->can('User')
                            ],
                            [
                                'label' => '<i class="fa fa-hdd-o"></i> <span>File Manager</span>',
                                'url' => ['/file-manager'],
                                'active' => \One::app()->controller->id == 'file-manager',
                                'visible' => \One::app()->user->can('File Manager')
                            ],
                        ],
                        'encodeLabels' => false,
                        'activateItems' => true,
                        'activateParents' => true,
                    ]);
                    ?>
                    <!-- /.sidebar-menu -->
                </section>
            </aside>

            <div class="content-wrapper">
                <section class="content-header">
                    <h1><?= $this->title ?></h1>

                    <?=
                    Breadcrumbs::widget([
                        'homeLink' => [
                            'label' => '<i class="fa fa-dashboard"></i> Dashboard',
                            'url' => ['dashboard/index'],
                            'template' => "<li><b>{link}</b></li>\n"
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'encodeLabels' => false,
                        'itemTemplate' => "<li>{link}</li>",
                    ])
                    ?>
                </section>

                <section class="content" id="content">
                    <?= $content ?>
                </section>
            </div>

            <footer class="main-footer">
                <!-- To the right -->
                <div class="pull-right hidden-xs">
                    <strong>Version <?= Html::encode(\One::app()->version) ?></strong>
                </div>
                <!-- Default to the left -->
                <strong>Copyright &copy; <?= date('Y') ?> <?= Html::encode(\One::app()->name) ?>.</strong> All rights reserved.
            </footer>

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
        </div>

        <div class="overlay-wrapper">
            <div class="overlay">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>


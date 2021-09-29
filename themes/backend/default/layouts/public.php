<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;

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

    <body class="hold-transition skin-blue layout-top-nav">
        <?php $this->beginBody() ?>

        <div class="wrapper">
            <header class="main-header">
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="<?= \One::app()->homeUrl ?>" class="navbar-brand"><b>One</b>CMS</a>
                        </div>
                    </div>
                </nav>
            </header>

            <div class="content-wrapper">
                <div class="container">
                    <section class="content-header">
                        <h1><?= $this->title ?></h1>

                        <?=
                        Breadcrumbs::widget([
                            'homeLink' => [
                                'label' => '<i class="fa fa-home"></i> Home',
                                'url' => ['site/index'],
                                'template' => "<li><b>{link}</b></li>\n"
                            ],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            'encodeLabels' => false,
                            'itemTemplate' => "<li>{link}</li>",
                        ])
                        ?>
                    </section>

                    <section class="content">
                        <?= $content ?>
                    </section>
                </div>
            </div>

            <footer class="main-footer">
                <div class="container">
                    <div class="pull-right hidden-xs">
                        <strong>Version <?= Html::encode(\One::app()->version) ?></strong>
                    </div>

                    <strong>Copyright &copy; <?= date('Y') ?> <?= \One::app()->name ?>.</strong> All rights reserved.
                </div>
            </footer>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
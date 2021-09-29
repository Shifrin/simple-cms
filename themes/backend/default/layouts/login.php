<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
$this->registerCssFile(\One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') . '/plugins/iCheck/all.css', [
    'depends' => [\yii\web\JqueryAsset::className()]
]);
$this->registerJsFile(\One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') . '/plugins/iCheck/icheck.min.js', [
    'depends' => [\yii\web\JqueryAsset::className()]
]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= \One::app()->language ?>">
    <head>
        <meta charset="<?= \One::app()->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title>
            <?= Html::encode(!empty($this->title) ? $this->title . ' - ' . \One::app()->name : \One::app()->name) ?>
        </title>
        <?php $this->head() ?>
    </head>

    <body class="hold-transition login-page">
        <?php $this->beginBody() ?>

        <div class="login-box">
            <div class="login-logo">
                <a href="<?= \One::app()->frontUrlManager->createUrl('/') ?>"><b>One</b>CMS Administration</a>
            </div>

            <div class="login-box-body">
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="pull-right hidden-xs">
                    <strong>Version <?= Html::encode(\One::app()->version) ?></strong>
                </div>

                <strong>Copyright &copy; <?= date('Y') ?> <?= \One::app()->name ?>.</strong> All rights reserved.
            </div>
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
                'timeout' => false,
            ],
            'registerFontAwesomeCss' => false,
            'registerButtonsCss' => false
        ]);
        ?>

        <div class="overlay-wrapper">
            <div class="overlay">
                <i class="fa fa-spinner fa-spin fa-fw"></i>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
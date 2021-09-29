<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
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

    <body class="hold-transition skin-blue layout-top-nav layout-error">
        <?php $this->beginBody() ?>

        <div class="wrapper">
            <div class="content-wrapper">
                <div class="container">
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
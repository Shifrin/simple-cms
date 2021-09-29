<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = One::t('app', 'Page not Found');
$this->params['pageHeader'] = $this->title;
?>

<!-- INNER CONTENT -->
<div class="inner-content">
    <div class="container">
        <div class="text-center error-404">
            <h2>404</h2>
            <p>Error 404! Sorry, the page you requested was not found.</p>
            <div class="clearfix"></div>
            <a href="<?= \yii\helpers\Url::home(['/']) ?>" class="button btn-center">
                <i class="icon-circle-arrow-left"></i>Back to Home
            </a>
        </div>
    </div>
</div>

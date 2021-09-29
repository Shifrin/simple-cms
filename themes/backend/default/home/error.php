<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\web\HttpException;

$code = $exception instanceof HttpException ? $exception->statusCode : $exception->getCode();
$message = $exception instanceof HttpException ? $exception->getMessage() : 'An internal server error occurred.';

if ($name === null) {
    $name = 'Error';
}

if ($code && strpos($name, "(#$code)") !== false) {
    $name = str_replace("(#$code)", '', $name) . " - $code";
}

$this->title = $name;
?>

<div class="error-page text-center">
    <h2 class="headline text-danger">
        <?= $code ? Html::encode($code) : Html::encode($name) ?>
    </h2>

    <div class="error-content">
        <h3>
            <i class="fa fa-times-circle text-danger"></i> <?= Html::encode($name) ?>
        </h3>

        <h2>
            <?= nl2br(rtrim(Html::encode($message), '.')) ?>
        </h2>

        <p>
            The above error occurred while the Web server was processing your request.
        </p>

        <p class="text-info">
            <i class="fa fa-warning"></i> Please contact us if you think this is a server error. Thank you.
        </p>
    </div>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$loginLink = One::app()->urlManager->createAbsoluteUrl(['site/login']);
?>

Hello,

Your user account has been created by our administrator, please use the following details login.

Username: <?= Html::encode($username) ?>
Password: <?= Html::encode($password) ?>

Follow the link below to login:

<?= $loginLink ?>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$loginLink = One::app()->urlManager->createAbsoluteUrl(['home/login']);
?>
<div class="new-user">
    <p>Hello,</p>

    <p>Your user account has been created by our administrator, please use the following details login.</p>

    <ul>
        <li>Username: <?= Html::encode($username) ?></li>
        <li>Password: <?= Html::encode($password) ?></li>
    </ul>

    <p>Follow the link below to login:</p>

    <p><?= Html::a(Html::encode($loginLink), $loginLink) ?></p>
</div>

<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\User */

$verificationLink = One::app()->urlManager->createAbsoluteUrl([
    'home/email-verification', 'token' => $user->email_verify_key
]);
?>
<div class="email-verification">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verificationLink), $verificationLink) ?></p>
</div>

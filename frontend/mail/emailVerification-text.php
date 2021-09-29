<?php

/* @var $this yii\web\View */
/* @var $user \common\models\User */

$verificationLink = One::app()->urlManager->createAbsoluteUrl([
    'home/email-verification', 'token' => $user->email_verify_key
]);
?>
Hello <?= $user->username ?>,

Follow the link below to verify your email:

<?= $verificationLink ?>

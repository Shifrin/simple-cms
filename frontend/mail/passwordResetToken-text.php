<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = urldecode(One::app()->urlManager->createAbsoluteUrl([
    '/reset-password', 'token' => $user->password_reset_token
]));
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $resetLink ?>

<?php

/* @var $this yii\web\View */
/* @var $user \common\models\User */

$url = \yii\helpers\Url::toRoute([
    'verify-email', 'token' => $user->email_verify_key
], true);
?>
Hello <?= $user->username ?>,

Follow the link below to verify your email:

<?= $url ?>

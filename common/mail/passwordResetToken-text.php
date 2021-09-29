<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$url = \yii\helpers\Url::toRoute([
    'reset-password', 'token' => $user->password_reset_token
], true);
?>
Hello <?= $user->username ?>,

Follow the link below to reset your password:

<?= $url ?>
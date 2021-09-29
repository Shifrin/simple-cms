<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = urldecode(One::app()->urlManager->createAbsoluteUrl([
    '/reset-password', 'token' => $user->password_reset_token
]));
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a($resetLink, $resetLink) ?></p>
</div>

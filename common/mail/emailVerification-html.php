<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\User */

$url = \yii\helpers\Url::toRoute([
    'verify-email', 'token' => $user->email_verify_key
], true);
?>
<div class="email-verification">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a($url, $url) ?></p>
</div>

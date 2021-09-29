<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$url = \yii\helpers\Url::toRoute([
    'reset-password', 'token' => $user->password_reset_token
], true);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><a href="<?= $url ?>"><?= $url ?></a></p>
</div>

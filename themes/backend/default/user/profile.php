<?php

/* @var $this yii\web\View */
/* @var $model \common\models\UserProfile */

$this->title = Yii::t('app', 'Your Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Your Profile');
?>

<div class="user-profile">
    <div class="row">
        <div class="col-md-4">
            <?= $this->render('_pictureform', [
                'model' => $model,
            ]) ?>
        </div>
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#info" aria-controls="info" role="tab" data-toggle="tab">Home</a>
                        </li>
                        <li role="presentation">
                            <a href="#password" aria-controls="password" role="tab" data-toggle="tab">Password Reset</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="info">
                            <?= $this->render('_profileform', [
                                'model' => $model,
                            ]) ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="password">
                            <?= $this->render('_passwordresetform', [
                                'model' => $passwordReset,
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Image;
use backend\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Image */

\backend\assets\BootstrapSwitchAsset::register($this);

$this->title = 'View Image: #' . $model->unique_id;
$this->params['breadcrumbs'][] = ['label' => One::t('app', 'Images'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="image-view">
    <h2 id="status" style="margin-bottom: 30px;"><?= $model->getStatus() ?></h2>

    <div class="row">
        <div class="col-md-6">
            <div class="text-center" style="margin-bottom: 20px;">
                <?= $model->getThumbnail('medium', [
                    'class' => 'img-responsive',
                    'style' => 'margin: 0 auto'
                ]) ?>
            </div>

            <div class="box box-default">
                <div class="box-header">
                    <h3 class="box-title">Image Information</h3>
                    <div class="box-tools pull-right">
                        <a href="<?= Url::to(['load-form']) ?>" class="btn btn-box-tool" data-toggle="tooltip" data-placement="top" title="Update Information">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <p><strong><?= $model->getAttributeLabel('title') . ':' ?></strong></p>
                    <p>
                        <?= !empty($model->title) ? $model->title :
                            '<i class="text-muted">Not Available</i>' ?>
                    </p>
                    <p><strong><?= $model->getAttributeLabel('description') . ':' ?></strong></p>
                    <p>
                        <?= !empty($model->description) ? $model->description :
                            '<i class="text-muted">Not Available</i>' ?>
                    </p>
                    <p><strong><?= $model->getAttributeLabel('category') . ':' ?></strong></p>
                    <p>
                        <?php
                        if (!empty($model->getCategory())) {
                            echo rtrim(implode(', ', $model->getCategory()), ', ');
                        } else {
                            echo '<i class="text-muted">Not Listed</i>';
                        }
                        ?>
                    </p>
                    <p><strong><?= $model->getAttributeLabel('created_at') . ':' ?></strong></p>
                    <p><?= One::app()->formatter->asDatetime($model->created_at) ?></p>
                    <p><strong><?= $model->getAttributeLabel('created_by') . ':' ?></strong></p>
                    <p><?= Html::encode($model->getAuthorName()) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <h2 style="margin-top: 0">File Properties</h2>

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="file-heading">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#file" aria-expanded="true" aria-controls="file">
                                <i class="ion-image"></i> File Information
                            </a>
                        </h4>
                    </div>

                    <div id="file" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="file-heading">
                        <div class="panel-body">
                            <dl class="dl-horizontal" style="margin-bottom: 0">
                                <?php foreach ($model->getImageInfo() as $key => $value) { ?>
                                    <dt><?= $key . ': ' ?></dt>
                                    <dd><?= $value ?></dd>
                                <?php } ?>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="camera-heading">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#camera" aria-expanded="true" aria-controls="camera">
                                <i class="ion-android-camera"></i> Camera Information
                            </a>
                        </h4>
                    </div>

                    <div id="camera" class="panel-collapse collapse" role="tabpanel" aria-labelledby="camera-heading">
                        <div class="panel-body">
                            <dl class="dl-horizontal" style="margin-bottom: 0">
                                <?php foreach ($model->getCameraInfo() as $key => $value) { ?>
                                    <dt><?= $key . ': ' ?></dt>
                                    <dd><?= $value ?></dd>
                                <?php } ?>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

<!--            --><?php //if (One::app()->user->identity->isAdmin) { ?>
                <div class="buttons" style="margin-bottom: 20px;">
                    <span class="approve" style="display: <?= $model->status == Image::STATUS_PENDING || $model->status == Image::STATUS_REJECTED ? 'inline' : 'none' ?>">
                        <?= Html::input('checkbox', 'approve', '', [
                            'id' => 'approve',
                            'data-on-text' => 'Approved',
                            'data-off-text' => 'Approve',
                            'data-on-color' => 'success',
                            'data-off-color' => 'success'
                        ]) ?>
                    </span>
                    <span class="reject" style="display: <?= $model->status == Image::STATUS_PENDING || $model->status == Image::STATUS_APPROVED ? 'inline' : 'none' ?>">
                        <?= Html::input('checkbox', 'reject', '', [
                            'id' => 'reject',
                            'data-on-text' => 'Rejected',
                            'data-off-text' => 'Reject',
                            'data-on-color' => 'danger',
                            'data-off-color' => 'danger'
                        ]) ?>
                    </span>
                </div>
<!--            --><?php //} ?>

            <a href="<?= Url::to(['download', 'id' => $model->unique_id]) ?>" class="btn btn-success" data-method="post">
                <i class="ion-ios-download-outline"></i> Download
            </a>
        </div>
    </div>

</div>

<?php
$js = "
    $('#approve').bootstrapSwitch();
    $('#reject').bootstrapSwitch();
    
    var approve = $('.approve');
    var reject = $('.reject');
    
    $('#approve').on('switchChange.bootstrapSwitch', function(event, state) {
        $.post('" . Url::to(['approve', 'id' => Html::encode($model->unique_id)]) . "', {state: state}, function(response) {
            if (response.success) {
                $('#status').fadeOut('slow', function() {
                    $('#status').html(response.status);
                    $('#status').fadeIn('fast');
                    approve.fadeOut('fast');
                    reject.fadeIn('slow');
                    var n = Noty();
                    $.noty.setText(n.options.id, '<i class=\"fa fa-check-circle\"></i> Image approved successfully.');
                    $.noty.setType(n.options.id, 'success');
                });
            }
        });
    });
    
    $('#reject').on('switchChange.bootstrapSwitch', function(event, state) {
        $.post('" . Url::to(['reject', 'id' => Html::encode($model->unique_id)]) . "', {state: state}, function(response) {
            if (response.success) {
                $('#status').fadeOut('slow', function() {
                    $('#status').html(response.status);
                    $('#status').fadeIn('fast');
                    reject.fadeOut('fast');
                    approve.fadeIn('slow');
                    var n = Noty();
                    $.noty.setText(n.options.id, '<i class=\"fa fa-check-circle\"></i> Image rejected successfully.');
                    $.noty.setType(n.options.id, 'success');
                });
            }
        });
    });
    
    $('body').on('click', 'a.btn-box-tool', function(e) {
        e.preventDefault();

        var Modal = $('#view-modal'),
            title = '" . One::t('app', 'Update Information') . "',
            key = '" . Html::encode($model->unique_id) . "',
            viewId = 'image-form' + key;

        Modal.modal('show');
        Modal.find('.overlay').fadeIn();

        loadView(viewId, {id: key}, '" . Url::to(['load-form']) . "', function(data) {
            Modal.find('.overlay').fadeOut();
            Modal.find('#modal-title').text(title);
            Modal.find('#modal-content').html(data);
        }, function() {
            Modal.find('.overlay').fadeOut();
            Modal.find('#modal-content').html('<p>Form failed to load, please try again!</p>');
        }, false);
    });
";

$this->registerJs($js);

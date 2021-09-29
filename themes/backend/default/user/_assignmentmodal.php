<?php

/* @var $this yii\web\View */

$this->registerCssFile(\yii\helpers\Url::to($this->assetManager->getPublishedUrl('@bower/admin-lte') .
    '/plugins/iCheck/all.css'));
$this->registerJsFile(\yii\helpers\Url::to($this->assetManager->getPublishedUrl('@bower/admin-lte') .
    '/plugins/iCheck/icheck.min.js'));
?>

<div class="permission-assignments">
    <?php foreach ($permissions as $name => $permission) { ?>
        <div class="group" style="margin-bottom: 25px;">
            <p class="text-muted" style="border-bottom: 1px solid #eee;">
                <strong><?php echo strtoupper($name);  ?></strong>
            </p>

            <div class="row">
                <div class="col-md-4">
                    <p><?= $name ?></p>
                </div>

                <div class="col-md-8">
                    <input type="checkbox" name="iCheck" value="<?= $name ?>"<?php echo array_key_exists($name, $assigned) ? ' checked' : '' ?>>
                </div>
            </div>

            <?php if (isset($permission['child'])) { ?>
                <?php foreach ($permission['child'] as $name => $child) { ?>
                    <div class="row">
                        <div class="col-md-4">
                            <p><?= $name ?></p>
                        </div>

                        <div class="col-md-8">
                            <input type="checkbox" name="iCheck" value="<?= $name ?>"<?php echo array_key_exists($name, $assigned) ? ' checked' : '' ?>>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php
$url = \yii\helpers\Url::to(['assign-permission', 'name' => $roleName]);
$js = <<< JS
    $(function() {
        $('input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });
        
        $('input[type="checkbox"]').on('ifChanged', function(e) {
            var isChecked = $(this).is(':checked');
            
            var data = {
                checked: isChecked,
                permission_name: $(this).val(),
            };
            
            $.post('$url', data, function(response) {
                var n = Noty();
                
                if (response.success) {
                    $.noty.setText(n.options.id, '<i class="fa fa-check-circle"></i> Permission assigned successfully.');
                    $.noty.setType(n.options.id, 'success');
                } else {
                    $.noty.setText(n.options.id, '<i class="fa fa-times-circle"></i> Permission assigned failed.');
                    $.noty.setType(n.options.id, 'error');
                }
            }, 'json');
        });
    });
JS;

$this->registerJs($js);
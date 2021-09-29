<?php

/* @var $this yii\web\View */

\backend\assets\MixitupAsset::register($this);
$this->registerJsFile(One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') .
    '/plugins/iCheck/icheck.min.js', ['depends' => \backend\assets\AdminLte::className()]);
$this->registerCssFile(One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') .
    '/plugins/iCheck/all.css', ['depends' => \backend\assets\AdminLte::className()]);
$this->registerJsFile(One::getAlias('@web/js') . '/file-manager.js', [
    'depends' => \backend\assets\MixitupAsset::className()]);
$this->registerCssFile(One::getAlias('@web/css') . '/filemanager.css', [
    'depends' => \backend\assets\AdminLte::className()]);

$this->title = Yii::t('app', 'Storage');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_folder-modal') ?>
<?= $this->render('_edit-modal') ?>
<?= $this->render('_upload-modal') ?>
<?= $this->render('_delete-modal') ?>

    <div class="storage-index">

        <div class="storage-controls">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon file-edit" data-toggle="modal" data-target="#edit-modal"><i class="ion-android-create"></i></span>
                    <span class="input-group-addon file-delete" data-toggle="modal" data-target="#delete-modal">
                        <i class="ion-trash-b"></i>
                    </span>
                        <span class="input-group-addon"><i class="ion-search"></i></span>
                        <?= \yii\helpers\Html::input('text', 'search', '', [
                            'class' => 'form-control', 'id' => 'file-search'
                        ]) ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="pull-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#folder-modal">
                            <i class="ion-ios-folder"></i> New Folder
                        </button>
                        <button class="btn btn-success" data-toggle="modal" data-target="#upload-modal">
                            <i class="ion-ios-upload-outline"></i> Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="file-manager">
            <div class="files-count pull-left">
                <span><?= count($files) . ' Files' ?></span>
            </div>

            <div class="pull-right filter-options">
                <button class="btn btn-default filter" data-filter="all">All</button>
                <button class="btn btn-default filter" data-filter=".folder">Folders</button>
                <button class="btn btn-default filter" data-filter=".file, .image">Files</button>
                <button class="btn btn-default filter" data-filter=".image">Images</button>
            </div>

            <div class="clearfix"></div>

            <?= \yii\widgets\Breadcrumbs::widget($breadcrumbs) ?>

            <?php if (count($files) > 0) { ?>
                <div class="row files" id="mixitup">
                    <?php foreach ($files as $i => $file) { ?>
                        <?php
                        $filterClass = $file['type'] == 'directory' ? 'folder' : 'file';

                        if (strpos($file['type'], 'image') !== false) {
                            $filterClass = 'image';
                        }
                        ?>

                        <div class="col-md-3 mix <?= $filterClass ?>" data-myorder="<?= $i ?>">
                            <?php $icon = $file['type'] == 'directory' ? 'ion-ios-folder' : 'ion-document'; ?>

                            <div class="file-wrap" title="<?= $file['name'] ?>" data-path="<?= \yii\helpers\Url::to(['storage/index', 'path' => $file['path']]) ?>">
                                <div class="file-icon">
                                    <?php if (strpos($file['type'], 'image') !== false) { ?>
                                        <img src="<?= $file['url'] ?>" class="img-responsive">
                                    <?php } else { ?>
                                        <i class="icon <?= $icon ?>"></i>
                                    <?php } ?>
                                </div>

                                <div class="file-details">
                                    <span class="name"><?= $file['name'] ?></span>
                                    <?php if ($file['size'] !== null) { ?>
                                        <span class="size"><?= $file['size'] . ' ' . One::t('app', 'bytes') ?></span>
                                    <?php } ?>
                                    <?php if ($file['items'] !== null) { ?>
                                        <span class="count"><?= $file['items'] . ' ' . One::t('app', 'Items') ?></span>
                                    <?php } ?>
                                    <input type="checkbox" class="file-checkbox" name="delete" data-value="<?= $file['name'] ?>">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="no-files">
                    <?= One::t('app', 'Nothing Found') ?>
                </div>
            <?php } ?>
        </div>

    </div>
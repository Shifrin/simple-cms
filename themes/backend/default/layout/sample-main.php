<?php
/* Theme Main Sample Layout File */
use yii\helpers\Html;
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= \One::app()->language ?>">
        <head>
            <!-- Charset added from the app config -->
            <meta charset="<?= \One::app()->charset ?>">

            <!-- Required to validate CSRF token -->
            <?= Html::csrfMetaTags() ?>

            <!-- Write a title -->
            <title></title>

            <?php $this->head() ?>
        </head>
    
        <body>
            <?php $this->beginBody() ?>

            <!-- Body content goes here -->

            <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>
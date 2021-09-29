<?php
/* Theme Partial Sample Layout File */
$this->params['pageHeader'] = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="inner-content">
    <div class="container">
        <?= $model->content ?>
    </div>
</div>
<?php

/* @var $this yii\web\View */

$this->title = One::t('app', 'Maintenance Mode');
$this->params['pageHeader'] = $this->title;
?>

<div class="inner-content mm-wrap">
    <div class="container padding80">
        <div class="row">
            <div class="col-md-12 center mm-content text-center">
                <h1>MAINTENANCE MODE</h1>
                <p class="lead">The website is undergoing some scheduled maintenance.<br>Please come back later.</p>
            </div>
        </div>
    </div>
</div>
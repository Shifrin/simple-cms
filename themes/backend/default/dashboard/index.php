<?php

/* @var $this yii\web\View */
use backend\assets\AppAsset;

$this->title = 'Dashboard';
$this->registerCssFile(\One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') . '/plugins/morris/morris.css', [
    'depends' => [AppAsset::className()]
]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js', [
    'depends' => [AppAsset::className()]
]);
$this->registerJsFile(\One::app()->assetManager->getPublishedUrl('@bower/admin-lte/') . '/plugins/morris/morris.min.js', [
    'depends' => [AppAsset::className()]
]);
?>

<!--<div class="row">-->
<!--    <div class="col-lg-3 col-xs-6">-->
<!--        <div class="small-box bg-aqua">-->
<!--            <div class="inner">-->
<!--                <h3>50</h3>-->
<!---->
<!--                <p>Today Sales</p>-->
<!--            </div>-->
<!--            <div class="icon">-->
<!--                <i class="ion ion-social-usd"></i>-->
<!--            </div>-->
<!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-lg-3 col-xs-6">-->
<!--        <div class="small-box bg-green">-->
<!--            <div class="inner">-->
<!--                <h3>53<sup style="font-size: 20px">%</sup></h3>-->
<!---->
<!--                <p>Bounce Rate</p>-->
<!--            </div>-->
<!--            <div class="icon">-->
<!--                <i class="ion ion-stats-bars"></i>-->
<!--            </div>-->
<!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-lg-3 col-xs-6">-->
<!--        <div class="small-box bg-yellow">-->
<!--            <div class="inner">-->
<!--                <h3>44</h3>-->
<!---->
<!--                <p>New Users</p>-->
<!--            </div>-->
<!--            <div class="icon">-->
<!--                <i class="ion ion-person-add"></i>-->
<!--            </div>-->
<!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-lg-3 col-xs-6">-->
<!--        <div class="small-box bg-red">-->
<!--            <div class="inner">-->
<!--                <h3>65</h3>-->
<!---->
<!--                <p>New Visitors</p>-->
<!--            </div>-->
<!--            <div class="icon">-->
<!--                <i class="ion ion-pie-graph"></i>-->
<!--            </div>-->
<!--            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--<div class="box box-primary">-->
<!--    <div class="box-header with-border">-->
<!--        <h3 class="box-title">Sales Chart</h3>-->
<!---->
<!--        <div class="box-tools pull-right">-->
<!--            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>-->
<!--            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="box-body chart-responsive">-->
<!--        <div class="chart" id="revenue-chart"></div>-->
<!--    </div>-->
<!--</div>-->
<!---->
<?php
//$js = <<< JS
//$(function () {
//    "use strict";
//
//    // AREA CHART
//    var area = new Morris.Area({
//          element: 'revenue-chart',
//          resize: true,
//          data: [
//                {y: '2012 Q1', item1: 2666, item2: 2666},
//                {y: '2012 Q2', item1: 2778, item2: 2294},
//                {y: '2012 Q3', item1: 4912, item2: 1969},
//                {y: '2012 Q4', item1: 3767, item2: 3597},
//                {y: '2013 Q1', item1: 6810, item2: 1914},
//                {y: '2013 Q2', item1: 5670, item2: 4293},
//                {y: '2013 Q3', item1: 4820, item2: 3795},
//                {y: '2013 Q4', item1: 15073, item2: 5967},
//                {y: '2014 Q1', item1: 10687, item2: 4460},
//                {y: '2014 Q2', item1: 8432, item2: 4489},
//                {y: '2014 Q3', item1: 8432, item2: 5544},
//                {y: '2014 Q4', item1: 8432, item2: 3344},
//                {y: '2015 Q1', item1: 10687, item2: 8906},
//                {y: '2015 Q2', item1: 8432, item2: 9806},
//                {y: '2015 Q3', item1: 8432, item2: 9000},
//                {y: '2015 Q4', item1: 8432, item2: 8500},
//                {y: '2016 Q1', item1: 10687, item2: 9600},
//          ],
//          xkey: 'y',
//          ykeys: ['item1', 'item2'],
//          labels: ['Item 1', 'Item 2'],
//          lineColors: ['#a0d0e0', '#3c8dbc'],
//          hideHover: 'auto'
//        });
//    });
//JS;
//
//$this->registerJs($js);



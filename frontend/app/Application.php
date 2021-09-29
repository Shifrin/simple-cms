<?php

namespace frontend\app;

use common\components\LayoutManager;
use one\app\WebApplication;
use yii\web\UrlManager;

/**
 * Application Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project onecms
 * @copyright 2016 Mohammed Shifreen
 *
 * @property LayoutManager $layoutManager Read only property, custom component.
 * @property UrlManager $backUrlManager Read only property, custom component.
 */
class Application extends WebApplication
{
    /**
     * Application Name.
     *
     * @var string
     */
    public $name = 'Arab Photo World';
}
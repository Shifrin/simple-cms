<?php

namespace frontend\controllers;

use yii\web\Controller;

/**
 * Page controller
 */
class PageController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'page' => [
                'class' => 'frontend\actions\PageAction',
            ]
        ];
    }
}

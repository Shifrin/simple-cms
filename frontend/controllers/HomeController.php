<?php

namespace frontend\controllers;

use common\models\Image;
use yii\web\Controller;

/**
 * Site controller
 */
class HomeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'page' => [
                'class' => 'frontend\actions\PageAction',
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Example of partial layout change
//        return One::app()->layoutManager->setPartialView('index');
        $images = Image::find()->limit(6)->approved()->all();

        return $this->render('index', [
            'images' => $images,
        ]);
    }

    /**
     * Displays maintenance page.
     *
     * @return string
     */
    public function actionMaintenance()
    {
        return $this->render('maintenance');
    }
}

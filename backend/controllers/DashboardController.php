<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class DashboardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
//        if (!\One::app()->user->can('Dashboard')) {
//            throw new ForbiddenHttpException('You are not authorized to access this page.');
//        }

        return $this->render('index');
    }
}
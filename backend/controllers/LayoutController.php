<?php

namespace backend\controllers;

use backend\models\Layout;
use backend\models\LayoutPartial;
use yii\base\ErrorException;
use yii\data\ArrayDataProvider;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class LayoutController extends Controller
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
                        'roles' => ['Webmaster']
                    ]
                ],
            ],
        ];
    }

    /**
     * List Layout models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $mainLayoutFiles = \One::app()->layoutManager->getMainLayouts();
        $partialLayoutFiles = \One::app()->layoutManager->getPartialLayouts();
        $mainArrayData = [];
        $partialArrayData = [];
        $i = 0;
        $x = 0;

        foreach ($mainLayoutFiles as $name => $fileName) {
            $mainArrayData[$i]['name'] = ucfirst(Inflector::camel2id($name));
            $mainArrayData[$i]['slug'] = $name;
            $mainArrayData[$i]['type'] = 'main';
            $i++;
        }

        foreach ($partialLayoutFiles as $name => $fileName) {
            $partialArrayData[$x]['name'] = ucfirst(Inflector::camel2id($name));
            $partialArrayData[$x]['slug'] = $name;
            $partialArrayData[$x]['type'] = 'partial';
            $x++;
        }

        $mainDataProvider = new ArrayDataProvider([
            'allModels' => $mainArrayData
        ]);
        $partialDataProvider = new ArrayDataProvider([
            'allModels' => $partialArrayData
        ]);

        return $this->render('index', [
            'mainDataProvider' => $mainDataProvider,
            'partialDataProvider' => $partialDataProvider,
        ]);
    }

    /**
     * Create a Layout|LayoutPartial model.
     * Once it's created it will redirect the index page.
     *
     * @param null|string $type Defaults to null means Main
     * @return string|\yii\web\Response
     * @throws ErrorException
     */
    public function actionCreate($type = 'main')
    {
        $model = $type == 'main' ? new Layout() : new LayoutPartial();
        $sampleFile = $model->getSample();
        $sampleCode = file_get_contents($sampleFile);
        $model->content = $sampleCode;

        if ($model->load(\One::app()->request->post()) && $model->save()) {
            \One::app()->session->setFlash('success', 'Layout file saved successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Update a Layout|LayoutPartial model.
     * Once it's created it will redirect the same page.
     *
     * @param string $slug layout name
     * @param null|string $type Defaults to null means Main
     * @return string|\yii\web\Response
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($slug, $type = 'main')
    {
        $model = $type == 'main' ? new Layout() : new LayoutPartial();
        $layoutFile = $type == 'main' ? Layout::find($slug) : LayoutPartial::find($slug);

        if ($layoutFile == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!is_readable($layoutFile)) {
            throw new ErrorException('Requested file is not readable.');
        }

        if ($model->load(\One::app()->request->post()) && $model->save()) {
            \One::app()->session->setFlash('success', 'Layout saved successfully.');
            return $this->refresh();
        }

        $fileContents = file_get_contents($layoutFile);
        $model->name = ucfirst(Inflector::camel2id($slug));
        $model->content = $fileContents;

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Delete a Layout|LayoutPartial model.
     *
     * @param string $slug
     * @param null|string $type Defaults to null means Main
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($slug, $type = 'main')
    {
        $layoutFile = $type == 'main' ? Layout::find($slug) : LayoutPartial::find($slug);
        
        if (file_exists($layoutFile)) {
            \One::app()->session->setFlash('success', 'Layout deleted successfully.');
            unlink($layoutFile);
            return $this->redirect(['index']);
        } else {
            throw new NotFoundHttpException('The requested layout model does not exist.');
        }
    }
}

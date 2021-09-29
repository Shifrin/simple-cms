<?php

namespace backend\controllers;

use common\models\Article;
use common\models\ArticleSearch;
use One;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(One::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->status = Article::STATUS_DRAFT;
        $model->publish_at = One::app()->formatter->asDatetime(time());
        $model->author_id = One::app()->user->id;

        if ($model->load(One::$app->request->post())) {
            if ($model->save()) {
                One::app()->session->setFlash('success', 'Article saved successfully.');
                return $this->redirect(['update', 'id' => $model->id]);
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->publish_at = $model->getPublishAt();

        if ($model->load(One::$app->request->post())) {
            if ($model->save()) {
                One::app()->session->setFlash('success', 'Article saved successfully.');
                return $this->refresh();
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->moveToTrash();
        return $this->redirect(['index']);
    }

    /**
     * Add category.
     *
     * @param integer $id Model ID
     * @throws NotFoundHttpException
     */
    public function actionAddCategory($id)
    {
        $model = $this->findModel($id);

        if ($model->load(One::app()->request->post()) && $model->linkCategory()) {
            return;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Remove category.
     *
     * @param integer $id Model ID
     * @throws NotFoundHttpException
     */
    public function actionDeleteCategory($id)
    {
        $model = $this->findModel($id);

        if ($model->load(One::app()->request->post()) && $model->unlinkCategory()) {
            return;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

<?php

namespace backend\controllers;

use One;
use common\models\Page;
use common\models\PageSearch;
use common\models\Revision;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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
     * Lists all Page models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(One::app()->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
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
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Page();
        $model->status = Page::STATUS_DRAFT;
        $model->publish_at = One::app()->formatter->asDatetime(time());

        if ($model->load(One::app()->request->post())) {
            if ($model->save()) {
                $message = $model->status == Page::STATUS_PUBLISH ? 'Page published successfully.' :
                    'Page saved successfully.';
                $redirect = $model->status == Page::STATUS_PUBLISH ? ['index'] :
                    ['update', 'id' => $model->id];

                One::app()->session->setFlash('success', $message);
                return $this->redirect($redirect);
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
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     * @param null $action
     * @return array|string|Response
     */
    public function actionUpdate($id, $action = null)
    {
        $model = $this->findModel($id);
        $model->publish_at = $model->getPublishAt();
        $model->recordRevision();
        $revision = $model->loadFromRevision($action);

        if ($model->load(One::app()->request->post())) {
            if ($model->save()) {
                if ($revision != null && $revision->type === Revision::TYPE_AUTO_SAVE) {
                    $revision->delete();
                }

                $message = $model->status == Page::STATUS_PUBLISH ? 'Page published successfully.' :
                    'Page saved successfully.';
                $redirect = $model->status == Page::STATUS_PUBLISH ? ['index'] :
                    ['update', 'id' => $model->id];

                One::app()->session->setFlash('success', $message);
                return $this->redirect($redirect);
            } else {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['errors' => $model->getErrors()];
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'revision' => $revision,
            ]);
        }
    }

    /**
     * Handle auto-saving of an existing model.
     *
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionAutoSave($id)
    {
        if (!One::app()->request->isAjax) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = $this->findModel($id);
        $revision = $model->getLastAutosaved();

        if ($revision == null) {
            $revision = new Revision();
            $revision->type = Revision::TYPE_AUTO_SAVE;
        }

        if ($model->load(One::app()->request->post())) {
            $revision->model_id = $id;
            $revision->model = 'Page';
            $revision->title = $model->title;
            $revision->slug = $model->slug;
            $revision->content = $model->content;

            if ($revision->save()) {
                One::app()->response->format = Response::FORMAT_JSON;
                return ['success' => true];
            }
        }
    }

    /**
     * Move an existing Page model into trash.
     * If moving is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionTrash($id)
    {
        $this->findModel($id)->moveToTrash();
        One::app()->session->setFlash('success', 'Page moved to trash successfully.');
        return $this->redirect(['index', 'tab' => 'trash']);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        One::app()->session->setFlash('success', 'Page deleted successfully.');
        return $this->redirect(['index', 'tab' => 'trash']);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

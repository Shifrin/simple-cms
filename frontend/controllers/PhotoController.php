<?php

namespace frontend\controllers;

use One;
use yii\data\Pagination;
use yii\filters\AccessControl;
use common\models\Image;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PhotoController implements the Front-end actions for Image model.
 */
class PhotoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'download' => ['post'],
                ],
            ],
        ];
    }

    /**
     * List all Image models or View single Image model.
     *
     * @param null|integer $id
     * @return string
     */
    public function actionIndex($id = null)
    {
        if ($id != null) {
            $model = $this->findModel($id);
            $relatedModels = Image::find()->where(['not in', 'id', [$model->id]])
                ->approved()->limit(3)->all();

            return $this->render('single', [
                'model' => $model,
                'relatedModels' => $relatedModels,
            ]);
        } else {
            $query = Image::find()->approved();
            $countQuery = clone $query;
            $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'defaultPageSize' => 12,
            ]);

            $models = $query->offset($pages->offset)->limit($pages->limit)->all();

            return $this->render('index', ['models' => $models, 'pages' => $pages]);
        }
    }

    /**
     * Download file.
     *
     * @param $id
     * @return $this
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $image = $this->findModel($id);
        $filePath = $image->getFilePath();

        if ($filePath == null) {
            One::app()->session->setFlash('error', One::t('app', 'Requested file not available for download.'));
            $this->redirect(['photo/index', 'id' => $id]);
        }

        if (!$image->recordDownload()) {
            One::app()->session->setFlash('error', One::t('app', 'There is an error while complete your download, please try again.'));
            $this->redirect(['photo/index', 'id' => $id]);
        }

//        One::app()->session->setFlash('success', One::t('app', 'Thank you for downloading a photo.'));
//        One::app()->response->redirect(['photo/index', 'id' => $id]);
        return One::app()->response->sendFile($filePath);
    }

    /**
     * Find a model.
     *
     * @param $id
     * @return Image The Image model
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Image::findByUniqueId($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

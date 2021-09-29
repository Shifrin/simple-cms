<?php

namespace frontend\controllers;

use common\models\Article;
use One;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BlogController implements the CRUD actions for News model.
 */
class BlogController extends Controller
{
    /**
     * List all News models or View single News model.
     *
     * @param null $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($slug = null)
    {
        if ($slug !== null) {
            $model = $this->findModel($slug);

            return $this->render('single', ['model' => $model]);
        }

        $query = Article::find()->published();
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'defaultPageSize' => 5,
        ]);

        $models = $query->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', ['models' => $models, 'pages' => $pages]);
    }

    /**
     * @param $slug
     * @return Article|null
     * @throws NotFoundHttpException
     */
    protected function findModel($slug)
    {
        if (($model = Article::findBySlug($slug)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
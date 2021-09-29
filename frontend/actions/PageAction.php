<?php

namespace frontend\actions;

use common\models\Page;
use common\models\Revision;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * PageAction Class File.
 * PageAction will help to display page models using a specified view.
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 */
class PageAction extends Action
{
    /**
     * The view file needs to be rendered.
     *
     * @var string
     */
    public $view = 'page';

    /**
     * @inheritdoc
     */
    public function run($slug, $action = null)
    {
        $page = Page::findBySlug($slug);
        $preview = $action == 'preview' ? true : false;
        $notFound = $page == null ? true : false;

        if (!$notFound && !$preview) {
            $notFound = $page->status !== Page::STATUS_PUBLISH ? true : false;
        }

        if ($notFound) {
            throw new NotFoundHttpException('The requested page does not exist.');
        } else {
            if ($preview) {
                $revision = Revision::find()->where([
                    'type' => Revision::TYPE_AUTO_SAVE, 'model_id' => $page->id, 'model' => 'Page'
                ])->one();
                $page->attributes = $revision->attributes;
            }

            $this->controller->view->title = $page->title;

            if ($page->main_layout) {
                \One::app()->layoutManager->setMainLayout($page->main_layout);
            }

            if ($page->partial_layout) {
                return \One::app()->layoutManager->setPartialView($page->partial_layout, [
                    'model' => $page
                ]);
            } else {
                return $this->controller->render($this->view ?: $this->id, ['model' => $page]);
            }
        }
    }
}
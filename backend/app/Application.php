<?php

namespace backend\app;

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
 * @property UrlManager $frontUrlManager Read only property, custom component.
 */
class Application extends WebApplication
{
    /**
     * Application Name
     *
     * @var string
     */
    public $name = 'ArabPhotoStore Admin';

    /**
     * @inheritdoc
	 * Un comment this function if you use 'https'
     */
	/*
    public function handleRequest($request)
    {
        if (!$request->isSecureConnection) {
            $secureUrl = str_replace('http', 'https', $request->absoluteUrl);
            return \One::app()->response->redirect($secureUrl, 301);
        } else {
            return parent::handleRequest($request);
        }
    }
	*/
}
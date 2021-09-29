<?php

/**
 * One Application Bootstrap File.
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 */
class One extends \yii\BaseYii
{
    /**
     * @return \backend\app\Application|\frontend\app\Application
     */
    public static function app()
    {
        return self::$app;
    }

    /**
     * @inheritdoc
     */
    public static function getVersion()
    {
        return '0.1 - Beta';
    }
}
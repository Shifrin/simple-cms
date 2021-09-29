<?php

namespace common\grid;

use yii\helpers\Html;

/**
 * ActionColumn Class File
 *
 * Add description here
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    /**
     * @var array html options to be applied to the [[initDefaultButtons()|default buttons]].
     * Html options for each button. The array keys are the button names without curly brackets.
     * If the button name is not specified, it don't work.
     *
     * ```php
     * [
     *     'view' => [
     *          'class' => 'btn btn-primary',
     *     ]
     * ],
     * ```
     */
    public $singleButtonOptions;

    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => \One::t('yii', 'View'),
                    'class' => 'btn btn-info btn-xs btn-view',
                    'aria-label' => \One::t('yii', 'View'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);

                if (isset($this->singleButtonOptions['view']))
                    $options = array_merge($options, $this->singleButtonOptions['view']);

                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => \One::t('yii', 'Update'),
                    'class' => 'btn btn-primary btn-xs btn-update',
                    'aria-label' => \One::t('yii', 'Update'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);

                if (isset($this->singleButtonOptions['update']))
                    $options = array_merge($options, $this->singleButtonOptions['update']);

                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => \One::t('yii', 'Delete'),
                    'class' => 'btn btn-danger btn-xs btn-delete',
                    'aria-label' => \One::t('yii', 'Delete'),
                    'data-confirm' => \One::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ], $this->buttonOptions);

                if (isset($this->singleButtonOptions['delete']))
                    $options = array_merge($options, $this->singleButtonOptions['delete']);

                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
            };
        }
    }
}
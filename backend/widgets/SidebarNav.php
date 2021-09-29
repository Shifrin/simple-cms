<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 01/20/2016
 * Time: 1:22 PM
 */

namespace backend\widgets;

use yii\bootstrap\Nav;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;


class SidebarNav extends Nav
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->route === null && \One::app()->controller !== null) {
            $this->route = \One::app()->controller->getRoute();
        }

        if ($this->params === null) {
            $this->params = \One::app()->request->getQueryParams();
        }

        if ($this->dropDownCaret === null) {
            $this->dropDownCaret = Html::tag('i', '', ['class' => 'fa fa-angle-left pull-right']);
        }

        Html::addCssClass($this->options, ['widget' => 'sidebar-menu']);
    }

    /**
     * @inheritdoc
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }

        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }

        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if ($items !== null) {
            Html::addCssClass($options, ['widget' => 'treeview']);

            if ($this->dropDownCaret !== '') {
                $label .= ' ' . $this->dropDownCaret;
            }

            if (is_array($items)) {
                if ($this->activateItems) {
                    $items = $this->isChildActive($items, $active);
                }

                $items = $this->renderDropdown($items, $item);
            }
        }

        if ($this->activateItems && $active) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    /**
     * @inheritdoc
     */
    protected function renderDropdown($items, $parentItem)
    {
        return SidebarDropdown::widget([
            'options' => ArrayHelper::getValue($parentItem, 'dropDownOptions', []),
            'items' => $items,
            'encodeLabels' => $this->encodeLabels,
            'clientOptions' => false,
            'view' => $this->getView(),
        ]);
    }
}
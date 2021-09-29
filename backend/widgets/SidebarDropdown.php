<?php
/**
 * Created by PhpStorm.
 * User: Mohammad
 * Date: 01/20/2016
 * Time: 1:36 PM
 */

namespace backend\widgets;

use yii\bootstrap\Dropdown;
use yii\bootstrap\Html;


class SidebarDropdown extends Dropdown
{

    /**
     * Initializes the widget.
     * If you override this method, make sure you call the parent implementation first.
     */
    public function init()
    {
        if ($this->submenuOptions === null) {
            // copying of [[options]] kept for BC
            // @todo separate [[submenuOptions]] from [[options]] completely before 2.1 release
            $this->submenuOptions = $this->options;
            unset($this->submenuOptions['id']);
        }

        Html::addCssClass($this->options, ['widget' => 'treeview-menu']);
    }

}
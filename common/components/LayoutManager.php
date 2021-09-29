<?php
namespace common\components;

use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

/**
 * LayoutManager Class File
 *
 * This is a component to manage layouts that was created by the users from the administration.
 *
 * @author Mohammad Shifreen
 * @project OneCMS
 * @copyright 2016 Mohammed Shifreen
 */
class LayoutManager extends \yii\base\Component
{
    private $_mainLayouts = [];
    private $_partialLayouts = [];
    /**
     * Main layouts path.
     * The path to save main layout files which is created in administration.
     *
     * @var string Defaults to '@themes/frontend/main-layouts'
     */
    public $mainLayoutsPath = '@themes/frontend/main-layouts';
    /**
     * Partial layouts path.
     * The path to save partial layout files which is created in administration.
     *
     * @var string Defaults to '@themes/frontend/partial-layouts'
     */
    public $partialLayoutsPath = '@themes/frontend/common-layouts';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->createDirectories();
        $this->findMainLayouts();
        $this->findPartialLayouts();
    }

    /**
     * Get main layouts path.
     *
     * @return string
     */
    public function getMainLayoutsPath() {
        return \One::getAlias($this->mainLayoutsPath);
    }

    /**
     * Get partial layouts path.
     *
     * @return string
     */
    public function getPartialLayoutsPath() {
        return \One::getAlias($this->partialLayoutsPath);
    }

    /**
     * Get found main layouts.
     *
     * @return array
     */
    public function getMainLayouts()
    {
        return $this->_mainLayouts;
    }

    /**
     * Get found partial layouts.
     *
     * @return array
     */
    public function getPartialLayouts()
    {
        return $this->_partialLayouts;
    }

    /**
     * Set current layout.
     *
     * @param $layout string Layout slug
     * @throws NotFoundHttpException
     */
    public function setMainLayout($layout)
    {
        if (array_key_exists($layout, $this->_mainLayouts)) {
            \One::app()->layoutPath = $this->getMainLayoutsPath();
            \One::app()->layout = $this->_mainLayouts[$layout];
        } else {
            throw new NotFoundHttpException('Layout not found!');
        }
    }

    /**
     * Set given partial layout as current view.
     *
     * @param string $layout
     * @param array $params
     * @return string
     * @throws NotFoundHttpException
     */
    public function setPartialView($layout, $params = [])
    {
        if (array_key_exists($layout, $this->_partialLayouts)) {
            \One::app()->controller->viewPath = $this->getPartialLayoutsPath();
            return \One::app()->controller->render($layout, $params);
        } else {
            throw new NotFoundHttpException('Layout not found!');
        }
    }

    /**
     * Find main layouts and set.
     */
    protected function findMainLayouts()
    {
        $mainLayoutsPath = $this->getMainLayoutsPath();
        $main = FileHelper::findFiles($mainLayoutsPath, [
            'only' => ['*.php'],
            'recursive' => false
        ]);

        foreach ($main as $key => $file) {
            $explode = explode('\\', $file);
            $name = str_replace('.php', '', end($explode));
            $this->_mainLayouts[$name] = end($explode);
        }
    }

    /**
     * Find partial layouts and set.
     */
    protected function findPartialLayouts()
    {
        $partialLayoutsPath = $this->getPartialLayoutsPath();
        $partial = FileHelper::findFiles($partialLayoutsPath, [
            'only' => ['*.php'],
            'recursive' => false
        ]);

        foreach ($partial as $key => $file) {
            $explode = explode('\\', $file);
            $name = str_replace('.php', '', end($explode));
            $this->_partialLayouts[$name] = end($explode);
        }
    }

    /**
     * Create given directories if not exists.
     *
     * @throws \yii\base\Exception
     */
    private function createDirectories()
    {
        $mainLayoutsPath = $this->getMainLayoutsPath();
        $partialLayoutsPath = $this->getPartialLayoutsPath();

        if (!is_dir($mainLayoutsPath)) {
            FileHelper::createDirectory($mainLayoutsPath);
        }

        if (!is_dir($partialLayoutsPath)) {
            FileHelper::createDirectory($partialLayoutsPath);
        }
    }
}
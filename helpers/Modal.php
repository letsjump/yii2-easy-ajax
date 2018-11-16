<?php
/**
 * Created by PhpStorm.
 * User: letsjump
 * Date: 31/10/18
 * Time: 11.59
 */

namespace letsjump\easyAjax\helpers;

use letsjump\easyAjax\EasyAjax;

class Modal extends EasyAjax
{
    public $settings = [];
    
    public function init()
    {
        $this->settings = parent::getSettings();
        parent::init();
    }
    
    /**
     * Insert the default modal html code into the application layout
     */
    public function inject()
    {
        echo $this->render($this->settings['viewPath'] . DIRECTORY_SEPARATOR . $this->settings['modal']['viewFile'],
            ['widget' => $this]);
    }
    
    /**
     * @param string $title
     * @param string $content
     * @param \yii\base\Model[] $models
     * @param string $size
     * @param array $options
     * @param string $footerView
     *
     * @return array
     */
    public function generate($content, $title, $models, $size, $options, $footerView)
    {
        return [
            'title'   => $title,
            'content' => $content,
            'footer'  => $this->getFooter($models, $footerView),
            'size'    => empty($size) ? \yii\bootstrap\Modal::SIZE_DEFAULT : $size,
            'options' => $options,
        ];
    }
    
    /**
     * @param \yii\base\Model[] $models
     * @param string $footerView
     *
     * @return string
     */
    private function getFooter($models, $footerView)
    {
        if ($footerView === false) {
            return '';
        } else if ($footerView !== null && $footerView !== false) {
            return $this->view->render(
                $this->settings['viewPath']
                . DIRECTORY_SEPARATOR
                . $footerView
            );
        } else {
            return $this->view->render(
                $this->settings['viewPath']
                . DIRECTORY_SEPARATOR
                . $this->settings['modal']['defaultViewFooter'], ['models' => $models]
            );
        }
    }
}
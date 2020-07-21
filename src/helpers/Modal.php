<?php
/**
 * Modal renders a modal window that can be toggled by clicking on a button.
 * This class il also able to insert a modal html template into the application layout
 *
 * You can fire a modal in the EasyAjax way by
 *
 *
 *
 *  * @package   yii2-easy-ajax
 *  * @author    Gianpaolo Scrigna <letsjump@gmail.com>
 *  * @link https://github.com/letsjump/yii2-easy-ajax
 *  * @copyright Copyright &copy; Gianpaolo Scrigna, beintech.it, 2017-2020
 *  * @version   1.0.0
 *
 */

namespace letsjump\easyAjax\helpers;

use letsjump\easyAjax\EasyAjaxBase;
use yii\bootstrap\Modal as BSModal;
use yii\helpers\ArrayHelper;

class Modal extends EasyAjaxBase
{
    const SIZE_SMALL = BSModal::SIZE_SMALL, SIZE_DEFAULT = BSModal::SIZE_DEFAULT, SIZE_LARGE = BSModal::SIZE_LARGE;
    
    public $content;
    public $title;
    public $formId;
    public $footer;
    public $size = self::SIZE_DEFAULT;
    public $footerView;
    
    
    public function init()
    {
        parent::init();
    }
    
    /**
     * @param \yii\base\Model[] $formId
     * @param string|null|false $footerView
     *
     * @return string
     */
    private function getFooter($formId, $footerView)
    {
        if ($footerView === false) {
            return '';
        }
        
        if ($footerView !== null) {
            return $this->view->render(
                $this->configuration['viewPath'] . DIRECTORY_SEPARATOR . $footerView
            );
        }
        
        return $this->view->render(
            $this->configuration['viewPath'] . DIRECTORY_SEPARATOR . $this->configuration['modal']['defaultViewFooter'],
            ['formId' => $formId]
        );
    }
    
    /**
     * Insert the default modal html code into the application layout
     */
    public function render()
    {
        return $this->view->render(
            $this->configuration['viewPath'] . DIRECTORY_SEPARATOR . $this->configuration['modal']['viewFile'],
            ['widget' => $this]
        );
    }
    
    /**
     * @return array
     */
    public function generate()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'footer'  => $this->getFooter($this->formId, $this->footerView),
            'size'    => empty($this->size) ? self::SIZE_DEFAULT : $this->size,
            'options' => $this->configuration,
        ];
    }
    
}

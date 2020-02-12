<?php
/**
 *
 *  * @package   yii2-easy-ajax
 *  * @author    Gianpaolo Scrigna <letsjump@gmail.com>
 *  * @link https://github.com/letsjump/yii2-easy-ajax
 *  * @copyright Copyright &copy; Gianpaolo Scrigna, beintech.it, 2017-2020
 *  * @version   1.0.0
 *
 */

/**
 * Created by PhpStorm.
 * User: letsjump
 * Date: 31/10/18
 * Time: 11.59
 */

namespace letsjump\easyAjax\helpers;

use letsjump\easyAjax\EasyAjax;
use Yii;
use yii\bootstrap\Modal as BSModal;

class Modal extends EasyAjax
{
    public $options = [];
    
    const SIZE_SMALL = BSModal::SIZE_SMALL, SIZE_DEFAULT = BSModal::SIZE_DEFAULT, SIZE_LARGE = BSModal::SIZE_LARGE;
    
    public function init()
    {
        $this->options = $this->getOptions();
        parent::init();
    }
    
    /**
     * Insert the default modal html code into the application layout
     */
    public function inject()
    {
        echo $this->render($this->options['viewPath'] . DIRECTORY_SEPARATOR . $this->options['modal']['viewFile'],
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
            'size'    => empty($size) ? self::SIZE_DEFAULT : $size,
            'options' => $options,
        ];
    }
    
    public function removeDuplicateBundles()
    {
        Yii::$app->assetManager->bundles = [
            'yii\bootstrap\BootstrapPluginAsset'  => false,
            'yii\bootstrap\BootstrapAsset'        => false,
            'yii\web\JqueryAsset'                 => false,
            'letsjump\EasyAjax\web\EasyAjaxAsset' => false
        ];
    }
    
    /**
     * @param \yii\base\Model[] $models
     * @param string|null|false $footerView
     *
     * @return string
     */
    private function getFooter($models, $footerView)
    {
        if ($footerView === false) {
            return '';
        }
        
        if ($footerView !== null) {
            return $this->view->render(
                $this->options['viewPath'] . DIRECTORY_SEPARATOR . $footerView
            );
        }
        
        return $this->view->render(
            $this->options['viewPath'] . DIRECTORY_SEPARATOR . $this->options['modal']['defaultViewFooter'],
            ['models' => $models]
        );
    }
}
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
 * Created by PhpStorm.00
 * User: letsjump
 * Date: 31/10/18
 * Time: 11.59
 */

namespace letsjump\easyAjax\helpers;

use letsjump\easyAjax\EasyAjax;
use Yii;
use yii\bootstrap\Modal as BSModal;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class Modal extends EasyAjax
{
    const SIZE_SMALL = BSModal::SIZE_SMALL, SIZE_DEFAULT = BSModal::SIZE_DEFAULT, SIZE_LARGE = BSModal::SIZE_LARGE;
    
    protected $content;
    protected $title;
    protected $form_id;
    protected $footer;
    protected $size = self::SIZE_DEFAULT;
    protected $footerView;
    
    public function init()
    {
        parent::init();
    }
    
    /**
     * Insert the default modal html code into the application layout
     */
    public function inject()
    {
        echo $this->render($this->defaultOptions['viewPath'] . DIRECTORY_SEPARATOR . $this->defaultOptions['modal']['viewFile'],
            ['widget' => $this]);
    }
    
    /**
     * @return array
     */
    public function generate()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
            'footer'  => $this->getFooter($this->form_id, $this->footerView),
            'size'    => empty($this->size) ? self::SIZE_DEFAULT : $this->size,
            'options' => ArrayHelper::merge($this->defaultOptions, $this->options),
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
     * @param array|string $models
     *
     * @return string
     */
    public static function getFormIdFromModelName($models)
    {
        return ! empty($models) ? strtolower(StringHelper::basename(get_class($models))) . '-form' : null;
    }
    
    /**
     * @param $models
     *
     * @return bool
     */
    public static function isNewRecord($models)
    {
        if ( ! is_array($models)) {
            $models = [$models];
        }
        
        foreach ($models as $model) {
            if (method_exists($model, 'isNewRecord')) {
                return true;
            }
        }
        
        return false;
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
                $this->defaultOptions['viewPath'] . DIRECTORY_SEPARATOR . $footerView
            );
        }
        
        return $this->view->render(
            $this->defaultOptions['viewPath'] . DIRECTORY_SEPARATOR . $this->defaultOptions['modal']['defaultViewFooter'],
            ['models' => $models]
        );
    }
}
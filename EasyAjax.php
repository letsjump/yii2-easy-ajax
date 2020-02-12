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

namespace letsjump\easyAjax;

use letsjump\easyAjax\helpers\Modal;
use letsjump\easyAjax\helpers\Notify;
use letsjump\easyAjax\web\AnimateAsset;
use letsjump\easyAjax\web\EasyAjaxAsset;
use letsjump\easyAjax\web\NotifyAsset;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

class EasyAjax extends Widget
{
    /**
     * @var bool $registerAssets
     * This should be always true, so the required assets are load with the page
     */
    public $registerAssets = true;
    
    /**
     * @var bool $publishNotifyAsset
     * Publish the bundled Bootstrap Notify asset.
     *
     * To avoid asset duplication, it can be set to false
     * if your application has another extension that implements the Notify plugin
     */
    public $publishNotifyAsset = true;
    
    /**
     * @var bool $publishNotifyAsset
     * Publish the bundled Animate asset.
     *
     * To avoid asset duplication, it can be set to false
     * if your application has another extension that implements the Animate asset
     */
    public $publishAnimateAsset = true;
    
    public $renderModal = true;
    
    protected $_defaultOptions = [
        'viewPath' => '@vendor/letsjump/yii2-easy-ajax/views',
        'trigger' => 'data-yea',
        'modal'    => [
            'viewFile'          => '_modal_default',
            'id'                => 'yea-modal',
            'title_id'          => '.modal-header h4',
            'content_id'        => '.modal-body',
            'footer_id'         => '.modal-footer',
            'autofocus'         => true,
            'snapshot'          => null,
            'defaultViewFooter' => '_modal_buttons'
        ],
        'notify'   => [
            'viewFile'       => '_notify_default',
            'iconSuccess'    => 'glyphicon glyphicon-ok-circle',
            'iconInfo'       => 'glyphicon glyphicon-info-sign',
            'iconWarning'    => 'glyphicon glyphicon-warning-sign',
            'iconDanger'     => 'glyphicon glyphicon-exclamation-sign',
            'clientSettings' => [
                // Refer to the Bootstrap Notify documentation for all the options available
                // http://bootstrap-notify.remabledesigns.com/
            ]
        ],
        'yea_extends' => []
    ];
    
    /**
     *
     */
    public function init()
    {
        $view = $this->getView();
        if($this->registerAssets === true) {
            $view->registerJsVar('yea_options', $this->getOptions(), View::POS_HEAD);
    
            // registering assets
            if ( ! Yii::$app->request->isAjax) {
                EasyAjaxAsset::register($view);
        
                if ($this->publishAnimateAsset === true) {
                    AnimateAsset::register($view);
                }
        
                if ($this->publishNotifyAsset === true) {
                    NotifyAsset::register($view);
                }
            }
        }
        
        parent::init();
    }
    
    /**
     * @return array
     */
    public function getOptions()
    {
        return isset(Yii::$app->params['easyAjax'])
            ? ArrayHelper::merge($this->_defaultOptions, Yii::$app->params['easyAjax'])
            : $this->_defaultOptions;
    }
    
    /**
     * @param string $message       The message on confirm window
     * @param string $url           the url to fire after click on "Ok"
     * @param bool $processResponse if the response has to be processed by easyAjax
     *
     * @return array
     */
    public static function confirm($message, $url, $processResponse = true)
    {
        return ['yea_confirm' => ['message' => $message, 'url' => $url, 'processResponse' => $processResponse]];
    }
    
    /**
     * @param string $url
     * @param bool $processResponse if the jQuery.get() response has to be passed by easyAjax response
     *
     * @return array
     */
    public static function redirectAjax($url, $processResponse = true)
    {
        return ['yea_redirect' => ['url' => $url, 'ajax' => true, 'processResponse' => $processResponse]];
    }
    
    /**
     * @param string $url
     *
     * @return array
     */
    public static function redirectJavascript($url)
    {
        return ['yea_redirect' => ['url' => $url, 'ajax' => false, 'processResponse' => false]];
    }
    
    /**
     * @param array $array [['#tagID'=>'content to be replaced with'], ['#tagID'=>'content to be replaced with'], ...]
     *
     * @return array
     */
    public static function contentReplace($array)
    {
        return ['yea_content_replace' => $array];
    }
    
    /**
     * @param $array
     *
     * @return array
     */
    public static function reloadPjax($array)
    {
        return ['yea_pjax_reload' => $array];
    }
    
    /**
     * @param $array
     *
     * @return array
     */
    public static function formValidation($array)
    {
        return ['yea_form_validation' => $array];
    }
    
    /**
     * @param $tab
     * @param null $content
     *
     * @return array
     */
    public static function tab($tab, $content = null)
    {
        return ['yea_tab' => ['tab' => $tab, 'content' => $content]];
    }
    
    /**
     * @param $title
     * @param $content
     * @param null $models
     * @param null $size
     * @param array $options
     * @param null $footer
     *
     * @return array
     */
    public static function modal($content, $title = null, $models = null, $size = null, $options = [], $footer = null)
    {
        return ['yea_modal' => (new Modal())->generate($content, $title, $models, $size, $options, $footer)];
    }
    
    /**
     * @param $content
     * @param $title
     * @param null $size
     * @param null $footer
     * @param array $options
     *
     * @return array
     */
    public static function modalBasic($content, $title = null, $size = null, $footer = null, $options = [])
    {
        return ['yea_modal' => (new Modal())->generate($content, $title, null, $size, $options, $footer)];
    }
    
    
    public static function modalClose()
    {
        return ['yea_modal_close' => true];
    }
    
    /**
     * Render a Success Notify with default settings
     *
     * @param string $message
     * @param null|string $title
     * @param array $settings
     *
     * @return array
     */
    public static function notifySuccess($message, $title = null, $settings = [])
    {
        $settings['type'] = 'success';
        $notify           = new Notify();
        
        return [
            'yea_notify' => $notify->generate($message, $title, $notify->settings['notify']['iconSuccess'], null, null,
                $settings)
        ];
    }
    
    /**
     * Render a Info Notify with default settings
     *
     * @param string $message
     * @param null|string $title
     * @param array $settings
     *
     * @return array
     */
    public static function notifyInfo($message, $title = null, $settings = [])
    {
        $settings['type'] = 'info';
        $notify           = new Notify();
        
        return [
            'yea_notify' => $notify->generate($message, $title, $notify->settings['notify']['iconInfo'], null, null,
                $settings)
        ];
    }
    
    /**
     * Render a Warning Notify with default settings
     *
     * @param string $message
     * @param null|string $title
     * @param array $settings
     *
     * @return array
     */
    public static function notifyWarning($message, $title = null, $settings = [])
    {
        $settings['type'] = 'warning';
        $notify           = new Notify();
        
        return [
            'yea_notify' => $notify->generate(
                $message,
                $title,
                $notify->settings['notify']['iconWarning'],
                null,
                null,
                $settings
            )
        ];
    }
    
    /**
     * Render a Danger Notify with default settings
     *
     * @param string $message
     * @param null|string $title
     * @param array $settings
     *
     * @return array
     */
    public static function notifyDanger($message, $title = null, $settings = [])
    {
        $settings['type'] = 'danger';
        $notify           = new Notify();
        
        return [
            'yea_notify' => $notify->generate($message, $title, $notify->settings['notify']['iconDanger'], null,
                null,
                $settings)
        ];
    }
    
    /**
     * Render a configurable Notify with default settings
     *
     * @param string $type
     * @param string $message
     * @param null|string $title
     * @param null|string $icon
     * @param null|string $url
     * @param null|string $target
     * @param array $settings
     *
     * @return array
     */
    public static function notify(
        $type,
        $message,
        $title = null,
        $icon = null,
        $url = null,
        $target = null,
        $settings = []
    ) {
        $settings['type'] = $type;
        
        return ['yea_notify' => (new Notify())->generate($message, $title, $icon, $url, $target, $settings)];
    }
    
    /**
     * 1. render the bootstrap modal element at the bottom of the document body
     * @return string|void
     */
    public function run()
    {
        // render the modal at the bottom of the doc body
        if ($this->renderModal === true) {
            (new Modal())->inject();
        }
        
        parent::run();
    }
    
}
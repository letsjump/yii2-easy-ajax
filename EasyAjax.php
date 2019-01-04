<?php
/**
 * @package   yii2-easy-ajax
 * @author    Gianpaolo Scrigna <letsjump@gmail.com>
 * @link https://github.com/letsjump/yii2-easy-ajax
 * @copyright Copyright &copy; Gianpaolo Scrigna, beintech.it, 2018
 * @version   $version
 */

namespace letsjump\easyAjax;

use letsjump\easyAjax\helpers\Modal;
use letsjump\easyAjax\helpers\Notify;
use letsjump\easyAjax\web\AnimateAsset;
use letsjump\easyAjax\web\EasyAjaxAsset;
use letsjump\easyAjax\web\NotifyAsset;
use Yii;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\web\View;

class EasyAjax extends Widget
{
    const SUCCESS = 'yea_success';
    
    /**
     * Growls
     */
    const NOTIFY = 'yea_notify';
    const NOTIFY_TYPE = 'type';
    const NOTIFY_ICON = 'icon';
    const NOTIFY_TITLE = 'title';
    
    const SAVE = 'yea-save';
    
    /*
     * Redirects
     */
    const REDIRECT = 'yea_redirect';
    
    /**
     * Reloads
     */
    const RELOAD_PJAX = 'yea_pjax_reload';
    const RELOAD_TAB = 'yea_tabs_reload';
    const RELOAD_AJAX = 'yea_ajax_reload';
    
    /**
     * Form validation
     */
    const FORM_VALIDATION = 'yea_form_validation';
    
    /**
     * Replaces
     */
    const CONTENT_REPLACE = 'yea_content_replace';
    
    /**
     * Confirms & alerts
     */
    const CONFIRM = 'yea_confirm';
    const CONFIRM_MESSAGE = 'message';
    const CONFIRM_URL = 'url';
    
    public $modal_id = "yea-modal";
    
    public $publishNotifyAsset = true;
    
    public $publishAnimateAsset = true;
    
    public $renderModal = true;
    
    protected $_defaultSettings = [
        'viewPath' => '@vendor/letsjump/yii2-easy-ajax/views',
        'modal'    => [
            'viewFile'          => '_modal_default',
            'modal_id'          => 'yea-modal',
            'defaultViewFooter' => '_modal_buttons'
        ],
        'notify'   => [
            'viewFile'       => '_notify_default',
            'iconSuccess'    => 'glyphicon glyphicon-ok-circle',
            'iconInfo'       => 'glyphicon glyphicon-info-sign',
            'iconWarning'    => 'glyphicon glyphicon-warning-sign',
            'iconDanger'     => 'glyphicon glyphicon-exclamation-sign',
            'clientSettings' => [
                'element'         => 'body',
                'position'        => null,
                'type'            => 'info',
                'allow_dismiss'   => true,
                'newest_on_top'   => false,
                'showProgressbar' => false,
                'placement'       => [
                    'from'  => 'top',
                    'align' => 'right'
                ],
                'offset'          => 20,
                'spacing'         => 10,
                'z_index'         => 1031,
                'delay'           => 5000,
                'timer'           => 1000,
                'url_target'      => '_blank',
                'mouse_over'      => null,
                'animate'         => [
                    'enter' => 'animated fadeInDown',
                    'exit'  => 'animated fadeOutUp',
                ],
                'onShow'          => null,
                'onShown'         => null,
                'onClose'         => null,
                'onClosed'        => null,
                'iconType'        => 'class'
            ]
        ]
    ];
    
    /**
     * Modals
     */
    const MODAL = 'yea_modal';
    const MODAL_CLOSE = 'yea_modal_close';
//    const MODAL_TITLE = 'title';
//    const MODAL_HEADER = 'header';
//    const MODAL_OPTIONS = 'options';
//    const MODAL_CONTENT = 'body';
//    const MODAL_FOOTER = 'footer';
//    const MODAL_SIZE = 'size';
//    const MODAL_ADDCSSCLASS = 'addClass';
    
    /**
     *
     */
    public function init()
    {
        $view = $this->getView();
        
        $view->registerJsVar('yea_modalid', $this->modal_id, View::POS_HEAD);
        
        // registering assets
        if ( ! Yii::$app->request->isAjax) {
            EasyAjaxAsset::register($view);
            
            if ($this->publishAnimateAsset == true) {
                AnimateAsset::register($view);
            }
            
            if ($this->publishNotifyAsset == true) {
                NotifyAsset::register($view);
            }
        }
        
        
        parent::init();
    }
    
    /**
     * @return array
     */
    public function getSettings()
    {
        return isset(Yii::$app->params['easyAjax'])
            ? ArrayHelper::merge($this->_defaultSettings, Yii::$app->params['easyAjax'])
            : $this->_defaultSettings;
    }
    
    /**
     * @param string $message The message on confirm window
     * @param string $url the url to fire after click on "Ok"
     * @param bool $processResponse if the response has to be processed by easyAjax
     *
     * @return array
     */
    public static function confirm($message, $url, $processResponse = true)
    {
        return [self::CONFIRM => ['message' => $message, 'url' => $url, 'processResponse' => $processResponse]];
    }
    
    /**
     * @param string $url
     * @param bool $processResponse if the jQuery.get() response has to be passed by easyAjax response
     *
     * @return array
     */
    public static function redirectAjax($url, $processResponse = true)
    {
        return [self::REDIRECT => ['url' => $url, 'ajax' => true, 'processResponse' => $processResponse]];
    }
    
    /**
     * @param string $url
     *
     * @return array
     */
    public static function redirectJavascript($url)
    {
        return [self::REDIRECT => ['url' => $url, 'ajax' => false, 'processResponse' => false]];
    }
    
    /**
     * @param array $array [['#tagID'=>'content to be replaced with'], ['#tagID'=>'content to be replaced with'], ...]
     *
     * @return array
     */
    public static function contentReplace($array)
    {
        return [self::CONTENT_REPLACE => $array];
    }
    
    /**
     * @param $array
     *
     * @return array
     */
    public static function reloadPjax($array){
        return [self::RELOAD_PJAX => $array];
    }
    
    /**
     * @param $array
     *
     * @return array
     */
    public static function formValidation($array)
    {
        return [self::FORM_VALIDATION => $array];
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
        $modal = new Modal();
        
        return [self::MODAL => $modal->generate($content, $title, $models, $size, $options, $footer)];
    }
    
    /**
     * @param $title
     * @param $content
     * @param null $size
     * @param array $options
     *
     * @return array
     */
    public static function modalBasic($content, $title = null, $size = null, $options = [])
    {
        return [self::MODAL => (new Modal())->generate($content, $title, null, $size, $options, $footer = false)];
    }
    
    
    public static function modalClose()
    {
        return [self::MODAL_CLOSE => true];
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
            self::NOTIFY => $notify->generate($message, $title, $notify->settings['notify']['iconSuccess'], null, null,
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
            self::NOTIFY => $notify->generate($message, $title, $notify->settings['notify']['iconInfo'], null, null,
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
            self::NOTIFY => (new Notify())->generate($message, $title, $notify->settings['notify']['iconWarning'], null,
                null,
                $settings)
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
            self::NOTIFY => (new Notify())->generate($message, $title, $notify->settings['notify']['iconDanger'], null,
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
        
        return [self::NOTIFY => (new Notify())->generate($message, $title, $icon, $url, $target, $settings)];
    }
    
    /**
     * @return string|void
     */
    public function run()
    {
        // render modal
        if ($this->renderModal == true) {
            (new Modal())->inject();
        }
        
        parent::run();
    }
    
}
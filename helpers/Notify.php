<?php
/**
 * Created by PhpStorm.
 * User: letsjump
 * Date: 31/10/18
 * Time: 11.59
 */

namespace letsjump\easyAjax\helpers;


use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class Notify extends Widget
{
    /**
     * Info type of the alert
     */
    const TYPE_INFO = 'info';
    /**
     * Danger type of the alert
     */
    const TYPE_DANGER = 'danger';
    /**
     * Success type of the alert
     */
    const TYPE_SUCCESS = 'success';
    /**
     * Warning type of the alert
     */
    const TYPE_WARNING = 'warning';
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => self::TYPE_DANGER,
        'danger'  => self::TYPE_DANGER,
        'success' => self::TYPE_SUCCESS,
        'info'    => self::TYPE_INFO,
        'warning' => self::TYPE_WARNING,
    ];
    
    public $viewPath = '@vendor/letsjump/yii2-easy-ajax';
    
    private $_settings = [
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
        'iconType'        => 'class',
        'template'        => null,
    ];
    
    public function init()
    {
        $this->_settings['template'] = $this->render($this->viewPath . '/views/_notify_default');
        parent::init();
    }
    
    public static function notifySuccess($message, $title = null, $settings = [])
    {
        $settings['type'] = 'success';
        
        return (new Notify())->generate($message, $title, $icon = 'glyphicon glyphicon-star', null, null, $settings);
    }
    
    public function generate($message, $title, $icon = null, $url = null, $target = null, $settings = [])
    {
        $options = [
            'message' => $message,
            'title'   => $title,
        ];
        if ( ! empty($icon)) {
            $options['icon'] = $icon;
        }
        if ( ! empty($url)) {
            $options['url'] = $url;
        }
        if ( ! empty($target)) {
            $options['target'] = $target;
        }
        
        return ['options' => $options, 'settings' => ArrayHelper::merge($this->_settings, $settings)];
    }
}
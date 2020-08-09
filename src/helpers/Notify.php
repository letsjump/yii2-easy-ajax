<?php
/*
 *
 *  * @package   yii2-easy-ajax
 *  * @author    Gianpaolo Scrigna <letsjump@gmail.com>
 *  * @link https://github.com/letsjump/yii2-easy-ajax
 *  * @copyright Copyright &copy; Gianpaolo Scrigna, beintech.it, 2017-2020
 *  * @version   1.0.1
 *
 */

/**
 * Created by PhpStorm.
 * User: letsjump
 * Date: 31/10/18
 * Time: 11.59
 */

namespace letsjump\easyAjax\helpers;

use letsjump\easyAjax\EasyAjaxBase;
use yii\helpers\ArrayHelper;

class Notify extends EasyAjaxBase
{
    /**
     * Notification default types
     */
    const TYPE_SUCCESS = 'success', TYPE_INFO = 'info', TYPE_WARNING = 'warning', TYPE_DANGER = 'danger';
    
    /**
     * @var string Notify message
     */
    public $message;
    
    /**
     * @var string Notify title
     */
    public $title;
    
    /**
     * @var string Notify icon
     */
    public $icon;
    
    /**
     * @var string Notify link url attribute
     */
    public $url;
    
    /**
     * @var string Notify link target attribute
     */
    public $target;
    
    /**
     * @var array Notify settings
     */
    public $settings;
    
    /**
     * Model init
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * @return array The Notify ajax response trigger
     */
    public function generate()
    {
        $options = [
            'message' => $this->message,
            'title'   => $this->title,
        ];
        if ( ! empty($this->icon)) {
            $options['icon'] = $this->icon;
        }
        if ( ! empty($this->url)) {
            $options['url'] = $this->url;
        }
        if ( ! empty($this->target)) {
            $options['target'] = $this->target;
        }
        if ( ! isset($this->defaultOptions['notify']['clientSettings']['template'])) {
            $this->getConfiguration()['notify']['clientSettings']['template'] = $this->getView()->render($this->getConfiguration()['viewPath']
                                                                                          . DIRECTORY_SEPARATOR
                                                                                          . $this->getConfiguration()['notify']['viewFile']);
        }
        
        return [
            'options'  => $options,
            'settings' => ArrayHelper::merge(
                $this->getConfiguration()['notify']['clientSettings'],
                $this->settings
            )
        ];
    }
}

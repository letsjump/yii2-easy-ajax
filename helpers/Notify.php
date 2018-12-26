<?php
/**
 * Created by PhpStorm.
 * User: letsjump
 * Date: 31/10/18
 * Time: 11.59
 */

namespace letsjump\easyAjax\helpers;


use letsjump\easyAjax\EasyAjax;
use Yii;
use yii\helpers\ArrayHelper;

class Notify extends EasyAjax
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
    
    public $settings = [];
    
    public function init()
    {
        $this->settings = parent::getSettings();
        
        if ( ! isset($this->settings['notify']['clientSettings']['template'])) {
            $this->settings['notify']['clientSettings']['template'] = $this->render($this->settings['viewPath']
                                                                                    . DIRECTORY_SEPARATOR
                                                                                    . $this->settings['notify']['viewFile']);
        }
        
        parent::init();
    }
    
    public function generate(
        $message,
        $title,
        $icon = null,
        $url = null,
        $target = null,
        $settings = []
    ) {
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
        
        return [
            'options'  => $options,
            'settings' => ArrayHelper::merge(
                $this->settings['notify']['clientSettings'],
                $settings
            )
        ];
    }
}
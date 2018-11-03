<?php
/**
 * Created by PhpStorm.
 * User: letsjump
 * Date: 31/10/18
 * Time: 11.59
 */

namespace letsjump\easyAjax\helpers;


use letsjump\easyAjax\EasyAjax;
use yii\helpers\ArrayHelper;

class Modal extends EasyAjax
{
    
    public function init()
    {
        $this->_settings['template'] = $this->render($this->viewPath . '/_notify_default');
        parent::init();
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
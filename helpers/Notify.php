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
use yii\helpers\ArrayHelper;

class Notify extends EasyAjax
{
    /**
     * @var string Notify message
     */
    protected $message;
    
    /**
     * @var string Notify title
     */
    protected $title;
    
    /**
     * @var string Notify icon
     */
    protected $icon;
    
    /**
     * @var string Notify link url attribute
     */
    protected $url;
    
    /**
     * @var string Notify link target attribute
     */
    protected $target;
    
    /**
     * @var array Notify settings
     */
    protected $settings;
    
    public function init()
    {
        parent::init();
    }
    
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
            $this->defaultOptions['notify']['clientSettings']['template'] = $this->render($this->defaultOptions['viewPath']
                                                                                          . DIRECTORY_SEPARATOR
                                                                                          . $this->defaultOptions['notify']['viewFile']);
        }
        
        return [
            'options'  => $options,
            'settings' => ArrayHelper::merge(
                $this->defaultOptions['notify']['clientSettings'],
                $this->settings
            )
        ];
    }
}
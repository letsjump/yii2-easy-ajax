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

namespace letsjump\easyAjax;

use letsjump\easyAjax\helpers\Modal;
use letsjump\easyAjax\helpers\Notify;

class EasyAjax extends EasyAjaxBase
{
    
    /**
     * @param string $message       Specifies the text to display in the confirm box
     * @param string $url           the url fired after click on "Ok"
     * @param bool $processResponse true if the response has to be processed by easyAjax
     *
     * @return array
     */
    public static function confirm($message, $url, $processResponse = true)
    {
        return ['yea_confirm' => ['message' => $message, 'url' => $url, 'processResponse' => $processResponse]];
    }
    
    /**
     * @param string $url           location href
     * @param bool $processResponse true if the jQuery.get() response has to be fired by easyAjax
     *
     * @return array
     */
    public static function redirectAjax($url, $processResponse = true)
    {
        return ['yea_redirect' => ['url' => $url, 'ajax' => true, 'processResponse' => $processResponse]];
    }
    
    /**
     * @param string $url location href
     *
     * @return array
     */
    public static function redirectJavascript($url)
    {
        return ['yea_redirect' => ['url' => $url, 'ajax' => false, 'processResponse' => false]];
    }
    
    /**
     * @param array $array #tagID => content for each tag to replace:
     *                     [['#tagID'=>'content to be replaced with'], ['#tagID'=>'content to be replaced with'], ...]
     *
     * @return array
     */
    public static function contentReplace($array)
    {
        return ['yea_content_replace' => $array];
    }
    
    /**
     * @param $array array #PjaxTagID => content for each pjax container to refresh:
     *               ['#pjaxContainerID', '#pjaxContainerID', ...]
     *
     * @todo check documentation description
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
     * @param string $tab
     * @param string|null $content
     *
     * @return array
     */
    public static function tab($tab, $content = null)
    {
        return ['yea_tab' => ['tab' => $tab, 'content' => $content]];
    }
    
    /**
     * @param string|null $title
     * @param string $content
     * @param string|null $form_id
     * @param string|null $size
     * @param array $options
     * @param string|null $footer
     *
     * @return array
     */
    public static function modal($content, $title = null, $form_id = null, $size = null, $options = [], $footer = null)
    {
        $modal             = new Modal();
        $modal->title      = $title;
        $modal->content    = $content;
        $modal->formId     = $form_id;
        $modal->size       = $size;
        $modal->options    = $options;
        $modal->footerView = $footer;
        
        return ['yea_modal' => $modal->generate()];
    }
    
    /**
     * @param $content
     * @param $title
     * @param string|null $size
     * @param string|null $footer
     * @param array $options
     *
     * @return array
     */
    public static function modalBasic($content, $title = null, $size = null, $footer = null, $options = [])
    {
        $modal             = new Modal();
        $modal->title      = $title;
        $modal->content    = $content;
        $modal->size       = $size;
        $modal->options    = $options;
        $modal->footerView = $footer;
        
        return ['yea_modal' => $modal->generate()];
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
        $settings['type'] = Notify::TYPE_SUCCESS;
        $notify           = new Notify();
        $notify->message  = $message;
        $notify->title    = $title;
        $notify->settings = $settings;
        $notify->icon     = $notify->getConfiguration()['notify']['iconSuccess'];
        
        return ['yea_notify' => $notify->generate()];
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
        $settings['type'] = Notify::TYPE_INFO;
        $notify           = new Notify();
        $notify->message  = $message;
        $notify->title    = $title;
        $notify->settings = $settings;
        $notify->icon     = $notify->getConfiguration()['notify']['iconInfo'];
        
        return ['yea_notify' => $notify->generate()];
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
        $settings['type'] = Notify::TYPE_WARNING;
        $notify           = new Notify();
        $notify->message  = $message;
        $notify->title    = $title;
        $notify->settings = $settings;
        $notify->icon     = $notify->getConfiguration()['notify']['iconWarning'];
        
        return ['yea_notify' => $notify->generate()];
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
        $settings['type'] = Notify::TYPE_DANGER;
        $notify           = new Notify();
        $notify->message  = $message;
        $notify->title    = $title;
        $notify->settings = $settings;
        $notify->icon     = $notify->getConfiguration()['notify']['iconDanger'];
        
        return ['yea_notify' => $notify->generate()];
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
        $notify           = new Notify();
        $notify->message  = $message;
        $notify->title    = $title;
        $notify->settings = $settings;
        $notify->icon     = $icon;
        $notify->url      = $url;
        $notify->target   = $target;
        $notify->settings = $settings;
        
        return ['yea_notify' => $notify->generate()];
    }
    
    public static function submit(){
        return isset($_POST['yea-submit']);
    }
    
}

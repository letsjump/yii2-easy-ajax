<?php
/**
 * Created by PhpStorm.
 * User: letsjump
 * Date: 29/10/18
 * Time: 11.28
 */

namespace letsjump\easyAjax;


use letsjump\easyAjax\web\AnimateAsset;
use letsjump\easyAjax\web\EasyAjaxAsset;
use letsjump\easyAjax\web\NotifyAsset;
use yii\bootstrap\Modal;
use yii\bootstrap\Widget;
use yii\web\View;

class EasyAjax extends Widget
{
    public $modal_id = "yea-modal";
    
    const SUCCESS = 'yea_success';
    
    /**
     * Modals
     */
    const MODAL = 'yea_modal';
    const MODAL_TITLE = 'title';
    const MODAL_HEADER = 'header';
    const MODAL_OPTIONS = 'options';
    const MODAL_CONTENT = 'body';
    const MODAL_FOOTER = 'footer';
    const MODAL_SIZE = 'size';
    const MODAL_ADDCSSCLASS = 'addClass';
    const MODAL_CLOSE = 'close';
    
    /**
     * Growls
     */
    const NOTIFY = 'yea_notify';
    const NOTIFY_TYPE = 'type';
    const NOTIFY_ICON = 'icon';
    const NOTIFY_TITLE = 'title';
    
    /*
     * Redirects
     */
    const REDIRECT = 'yea_js_redirect';
    const REDIRECT_AJAX = 'yea_ajax_redirect';
    
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
    const CONTENT_REPLACE = 'yea_replace';
    
    /**
     * Confirms & alerts
     */
    const CONFIRM = 'yea_confirm';
    const CONFIRM_MESSAGE = 'message';
    const CONFIRM_URL = 'url';
    
    public function init()
    {
        $this->getView()->registerJsVar('yea_modalid', $this->modal_id, View::POS_HEAD);
        EasyAjaxAsset::register($this->view);
        AnimateAsset::register($this->view);
        NotifyAsset::register($this->view);
        $this->renderModal();
        parent::init();
    }
    
    /**
     *
     */
    protected function renderModal()
    {
        Modal::begin([
            'header'  => '<h4 class="modal-title">Title</h4>',
            'footer'  => '',
            'id'      => $this->modal_id,
            'size'    => Modal::SIZE_DEFAULT,
            'options' => [
                'tabindex' => false, // important for Select2 to work properly
//            'class' => 'phantomModal',
            ],
        ]);
        echo "<div id='systemModalContent'></div>";
        Modal::end();
    }
}
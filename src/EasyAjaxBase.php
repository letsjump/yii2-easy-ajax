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

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\web\View;
use letsjump\easyAjax\helpers\Modal;
use letsjump\easyAjax\web\EasyAjaxAsset;
use letsjump\easyAjax\web\NotifyAsset;

class EasyAjaxBase extends Component
{
    /**
     * @var bool $registerAssets
     * This should be always true, so the required assets are loaded within the page
     */
    public $registerAssets = true;
    
    /**
     * @var bool $publishNotifyAsset
     * Publish the bundled Bootstrap Notify asset.
     *
     * To avoid asset duplication, it can be set to false.
     * Useful if your application has another extension
     * that implements the Notify plugin
     */
    public $publishNotifyAsset = true;
    
    /**
     * @var bool $renderModal
     * Render the default modal html structure into
     * the application main layout
     *
     * @see helpers/Modal.php
     */
    public $renderModal = true;
    
    protected $baseOptions = [
        'viewPath'    => '@vendor/letsjump/yii2-easy-ajax/src/views',
        'trigger'     => 'data-yea',
        'modal'       => [
            'viewFile'          => '_modal_default',
            'id'                => 'yea-modal',
            'title_id'          => '.modal-header h4',
            'content_id'        => '.modal-body',
            'footer_id'         => '.modal-footer',
            'autofocus'         => true,
            'snapshot'          => null,
            'defaultViewFooter' => '_modal_buttons'
        ],
        'notify'      => [
            'viewFile'       => '_notify_default',
            'iconSuccess'    => 'glyphicon glyphicon-ok-circle',
            'iconInfo'       => 'glyphicon glyphicon-info-sign',
            'iconWarning'    => 'glyphicon glyphicon-warning-sign',
            'iconDanger'     => 'glyphicon glyphicon-exclamation-sign',
            'clientSettings' => [
                // Refer to the Bootstrap Notify documentation for any other option available
                // http://bootstrap-notify.remabledesigns.com/
            ]
        ],
        'yea_extends' => []
    ];
    
    /**
     * @var array $customOptions
     * Per application custom configuration
     */
    public $customOptions = [];
    
    /**
     * @var array $options
     * Single plugin specific configuration
     */
    protected $options = [];
    
    /**
     * @var array $configuration
     * Merged $*option configuration
     */
    protected $configuration = [];
    
    /**
     * @var \yii\web\View $view
     * App view object shortcut variable
     */
    protected $view;
    
    /**
     * Component initialization
     */
    public function init()
    {
        $this->view = Yii::$app->view;
        $this->configuration = $this->getConfiguration();
        
        if ($this->registerAssets === true) {
            
            $this->view->registerJsVar('yea_options', $this->configuration, View::POS_HEAD);
            
            // registering assets
            if ( ! Yii::$app->request->isAjax) {
                EasyAjaxAsset::register($this->view);
                
                if ($this->publishNotifyAsset === true) {
                    NotifyAsset::register($this->view);
                }
            }
        }
        
        if ($this->renderModal === true) {
            $this->view->on(View::EVENT_END_BODY, function () {
                echo (new Modal())->render();
            });
        }
        
        parent::init();
    }
    
    /**
     * @return array final component configuration
     */
    public function getConfiguration()
    {
        return ArrayHelper::merge($this->baseOptions, $this->customOptions, $this->options);
    }
    
}

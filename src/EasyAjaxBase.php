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

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\web\View;
use letsjump\easyAjax\web\EasyAjaxAsset;
use letsjump\easyAjax\web\NotifyAsset;

/**
 *
 * @property \yii\base\View|\yii\web\View $view
 * @property-read array $configuration
 */
class EasyAjaxBase extends Component
{
    
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
        'extends' => []
    ];
    
    /**
     * @var array $customOptions
     * Per application custom configuration
     */
    public $customOptions;
    
    /**
     * @var array $options
     * Single plugin specific configuration
     */
    protected $options = [];
    
    /**
     * @var array $configuration
     * Merged $*option configuration
     */
//    protected $configuration = [];
    
    private $_configuration;
    
    /**
     * @var View the view object that can be used to render views or view files.
     */
    private $_view;
    
    /**
     * Component initialization
     */
    public function init()
    {
        parent::init();
        if($this->customOptions === null && Yii::$app->components['easyAjax']['customOptions'] !== false) {
            $this->customOptions = Yii::$app->components['easyAjax']['customOptions'];
        }
    }
    
    public function inject()
    {
        $this->getView()->registerJsVar('yea_options', $this->getConfiguration(), View::POS_HEAD);
        EasyAjaxAsset::register($this->getView());
        if ($this->publishNotifyAsset === true) {
            NotifyAsset::register($this->getView());
        }
        if ($this->renderModal === true) {
            $this->getView()->on(View::EVENT_END_BODY, function () {
                echo $this->getView()->render(
                    $this->getConfiguration()['viewPath'] . DIRECTORY_SEPARATOR . $this->getConfiguration()['modal']['viewFile'],
                    ['component' => $this]
                );
            });
        }
    }
    
    /**
     * Returns the view object that can be used to render views or view files.
     * The [[render()]], [[renderPartial()]] and [[renderFile()]] methods will use
     * this view object to implement the actual view rendering.
     * If not set, it will default to the "view" application component.
     * @return \yii\base\View|\yii\web\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = Yii::$app->getView();
        }
        
        return $this->_view;
    }
    
    /**
     * Sets the view object to be used by this controller.
     * @param View|\yii\web\View $view the view object that can be used to render views or view files.
     */
    public function setView($view)
    {
        $this->_view = $view;
    }
    
    /**
     * @return array component configuration
     */
    public function getConfiguration()
    {
        if(empty($this->_configuration)) {
            $this->_configuration = ArrayHelper::merge($this->baseOptions, $this->customOptions, $this->options);
        }
        return $this->_configuration;
    }
    
}

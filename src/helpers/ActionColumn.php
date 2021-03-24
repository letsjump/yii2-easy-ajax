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
 * Date: 17/01/17
 * Time: 9.40
 */

namespace letsjump\easyAjax\helpers;

use letsjump\easyAjax\EasyAjaxBase;
use yii\helpers\Html;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 *
 * @property array $defaultOptions
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    const RENDER_A = 1, RENDER_BUTTON = 2;
    const BUTTON_LARGE = ' btn-group-lg', BUTTON_DEFAULT = '', BUTTON_SMALL = 'btn-group-sm', BUTTON_XSMALL = 'btn-group-xs';
    
    public $modal = false;
    public $render = self::RENDER_BUTTON;
    public $buttonsSize = self::BUTTON_SMALL;
    public $buttonOptions = [];
    public $icons = [];
    public $buttonWidth = 45;
    public $columnWidth = 0;
    protected $_defaultOptions;
    protected $_mergedOptions;
    protected $yea_options;
    
    public function init()
    {
        parent::init();
        $this->initDefaultButtons();
        $this->headerWidth();
        $this->yea_options = (new EasyAjaxBase())->getConfiguration();
    }
    
    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view');
        $this->initDefaultButton('update');
        $this->initDefaultButton('delete');
    }
    
    /**
     * Initializes the default button rendering callback for single button
     *
     * @param string $name             Button name
     * @param string|null $iconName
     * @param array $additionalOptions Array of additional options
     *
     * @since 2.0.11
     */
    protected function initDefaultButton($name, $additionalOptions = [], $iconName = null)
    {
        if ( ! isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model) use ($name, $additionalOptions) {
                $options = $this->initAdditionalOptions($name);
                
                return Html::a(
                    $this->getIcon($name),
                    ! isset($options['action']) ? $url : Url::to([$options['action'], 'id' => $model->id]),
                    $options
                );
            };
        }
    }
    
    protected function initAdditionalOptions($button)
    {
        if (empty($this->_mergedOptions)) {
            $this->_mergedOptions = ArrayHelper::merge($this->getConfiguration(), $this->buttonOptions);
        }
        
        return $this->_mergedOptions[$button];
    }
    
    protected function getConfiguration()
    {
        if (empty($this->_defaultOptions)) {
            $title                 = [
                'view'   => Yii::t('yii', 'View'),
                'update' => Yii::t('yii', 'Update'),
                'delete' => Yii::t('yii', 'Delete')
            ];
            $this->_defaultOptions = [
                'view'   => [
                    'title'      => $title['view'],
                    'aria-label' => $title['view'],
                    'data-pjax'  => '0',
                ],
                'update' => [
                    'title'      => $title['update'],
                    'aria-label' => $title['update'],
                    'data-pjax'  => '0',
                ],
                'delete' => [
                    'title'      => $title['delete'],
                    'aria-label' => $title['delete'],
                    'data-pjax'  => '0',
                    //'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    //'data-method'  => 'post',
                ],
            ];
            
            if ($this->modal === true) {
                $this->_defaultOptions['view'][$this->yea_options['trigger']]          = 1;
                $this->_defaultOptions['update'][$this->yea_options['trigger']]        = 1;
                $this->_defaultOptions['update']['action']           = 'modal';
                $this->_defaultOptions['delete'][$this->yea_options['trigger']]        = 1;
                $this->_defaultOptions['delete']['data-yea-method']  = 'post';
                $this->_defaultOptions['delete']['data-yea-confirm'] = Yii::t('yii',
                    'Are you sure you want to delete this item?');
            }
            
            foreach ($this->_defaultOptions as $button => $option) {
                if ($this->render === self::RENDER_BUTTON) {
                    $this->_defaultOptions[$button]['role'] = 'button';
                }
                $this->_defaultOptions[$button]['class'] = ! isset($this->_defaultOptions[$button]['class'])
                    ? ' btn btn-default'
                    : $this->_defaultOptions[$button]['class'] . ' btn btn-default';
            }
            
            return $this->_defaultOptions;
        }
        
        return $this->_defaultOptions;
    }
    
    /**
     * @param $button
     *
     * @return mixed|string
     */
    protected function getIcon($button)
    {
        if ( ! isset($this->icons[$button])) {
            $iconName = '';
            switch ($button) {
                case 'view':
                    $iconName = 'eye-open';
                    break;
                case 'update':
                    $iconName = 'pencil';
                    break;
                case 'delete':
                    $iconName = 'trash';
                    break;
            }
            
            return Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
        }
        
        return $this->icons[$button];
    }
    
    protected function headerWidth()
    {
        $width = 'width: ' . $this->buttonWidth * substr_count($this->template, '{') . 'px;';
        if ( ! isset($this->headerOptions['style'])) {
            $this->headerOptions['style'] = $width;
        } else if (strpos($this->headerOptions['style'], 'width:') === false) {
            $this->headerOptions['style'] = $width . ' ' . $this->headerOptions['style'];
        }
        
    }
    
    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $buttons = preg_replace_callback('/{([\w\-\/]+)}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
            
            if (isset($this->visibleButtons[$name])) {
                $isVisible = $this->visibleButtons[$name] instanceof \Closure
                    ? call_user_func($this->visibleButtons[$name], $model, $key, $index)
                    : $this->visibleButtons[$name];
            } else {
                $isVisible = true;
            }
            
            if ($isVisible && isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);
                
                return call_user_func($this->buttons[$name], $url, $model, $key);
            }
            
            return '';
            
        }, $this->template);
        
        return $this->render === self::RENDER_A
            ? $buttons
            : Html::tag('div', $buttons, ['role' => 'group', 'class' => 'btn-group ' . $this->buttonsSize]);
    }
}

<?php
namespace letsjump\easyAjax\web;

use yii\web\AssetBundle as BaseEasyAjaxAsset;

/**
 * AdminLte AssetBundle
 * @since 0.1
 */
class EasyAjaxAsset extends BaseEasyAjaxAsset
{
    //public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $js = [
        'assets/main.js'
    ];
    public $depends = [
        //'rmrevin\yii\fontawesome\AssetBundle',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    const GROWL = 'growl';
    const GROWL_TYPE = 'type';
    const GROWL_ICON = 'icon';
    const GROWL_TITLE = 'title';
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}

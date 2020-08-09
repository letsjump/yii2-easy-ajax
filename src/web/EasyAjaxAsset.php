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

namespace letsjump\easyAjax\web;

use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 *
 * @since 0.1
 */
class EasyAjaxAsset extends AssetBundle
{
    
    public $sourcePath = '@vendor/letsjump/yii2-easy-ajax/src/assets';
    
    public $js = [
        'yii2-easy-ajax.js'
    ];
    
    public $depends = [
        'yii\web\YiiAsset',
    ];
    
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }
    
}

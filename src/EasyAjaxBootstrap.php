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
use yii\base\BootstrapInterface;

class EasyAjaxBootstrap implements BootstrapInterface
{
    
    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        if (Yii::$app instanceof \yii\web\Application
            && ! Yii::$app->request->isConsoleRequest
            && ! Yii::$app->request->isAjax
        ) {
            (new EasyAjaxBase())->inject();
        }
    }
}
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


use letsjump\easyAjax\helpers\Modal;
use yii\helpers\Html;

/**
 * @var $formId string
 * @var $this   \yii\web\View
 *
 */

$buttons = [];

if ($formId !== null) {
    
    $buttons['cancel'] = Html::button(
        Yii::t('app', 'Cancel'),
        [
            'class'        => 'btn btn-default',
            'data-dismiss' => 'modal'
        ]
    );
    
    $message = '';
    
    $buttons['save'] = Html::submitButton(
        Yii::t('app', 'Save'),
        [
            'class'       => 'btn btn-primary pull-right',
            'data-yea' => 1,
            'data-form-id' => $formId
        ]
    );
} else {
    $buttons['ok'] = Html::button(
        Yii::t('app', 'Ok'),
        [
            'class'        => 'btn btn-primary',
            'data-dismiss' => 'modal'
        ]
    );
}

foreach ($buttons as $button) {
    echo $button;
}

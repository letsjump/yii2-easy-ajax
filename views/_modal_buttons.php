<?php

use mdm\admin\components\Helper;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Json;

/**
 * @var $models \yii\base\Model
 * @var $this   \yii\web\View
 *
 */
$formId = [];
if ( ! empty($models)) {
    if ( ! is_array($models)) {
        $models = [$models];
    }
    foreach ($models as $model) {
        $formId[] = strtolower(StringHelper::basename(get_class($model))) . '-form';
    }
}

$buttons['cancel'] = Html::button(
    Yii::t('sys', 'Annulla'),
    [
        'class'        => 'btn btn-default',
        'data-dismiss' => 'modal'
    ]
);

$message = (isset($models) && method_exists($models[0], 'isNewRecord'))
    ? Yii::t('app', 'Add')
    : Yii::t('app', 'Save');

$buttons['save'] = Html::submitButton(
    $message,
    [
        'class'       => 'btn btn-info pull-right modalform-submit',
        'data-formid' => ! empty($formId) ? Json::encode($formId) : null
    ]
);

foreach ($buttons as $button) {
    echo $button;
}

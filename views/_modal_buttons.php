<?php

use mdm\admin\components\Helper;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/**
 * @var $model \common\components\ActiveRecord
 * @var $this  \yii\web\View
 *
 * Default buttons for Active Record based forms.
 */

if (empty($formId)) {
    $formId = strtolower(StringHelper::basename(get_class($model))) . '-form';
}

$this->registerJsVar('ajaxFormId', '#' . $formId);

$buttons['cancel'] = Html::button(
    Yii::t('sys', 'Annulla'),
    [
        'class'        => 'btn btn-default',
        'data-dismiss' => 'modal'
    ]
);
if(
    Helper::checkRoute('create')
    || Helper::checkRoute('modal')
    || Helper::checkRoute('update')
) {
    $buttons['save'] = Html::submitButton(
        $model->isNewRecord ? Yii::t('sys', 'Salva e aggiungi') : Yii::t('sys', 'Salva'),
        [
            'class' => 'btn btn-info pull-right',
            'id'    => 'modalform-submit',
            'data-formid' => $formId
        ]
    );
}

foreach ($buttons as $button) {
    echo $button;
}

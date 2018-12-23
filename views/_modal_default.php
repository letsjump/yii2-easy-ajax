<?php

/**
 * @var \letsjump\easyAjax\EasyAjax $widget
 */

use yii\bootstrap\Modal;

?>

<?php Modal::begin([
    'header'  => '<h4 class="modal-title">Title</h4>',
    'footer'  => '',
    'id'      => $widget->settings['modal']['modal_id'],
    'size'    => Modal::SIZE_DEFAULT,
    'options' => [
        'tabindex' => false, // important for Select2 to work properly
//      'class' => 'phantomModal',
    ],
]) ?>
    <div id='systemModalContent'></div>
<?php Modal::end(); ?>
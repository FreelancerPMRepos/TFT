<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'log_type')->dropDownList([ 'training' => 'Training', 'image' => 'Image', 'notes' => 'Notes', 'body' => 'Body', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'log_date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

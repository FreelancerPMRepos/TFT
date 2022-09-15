<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'meta_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meta_name')->textInput(['maxlength' => true]) ?>

    <?php
     echo $form->field($model, 'meta_type')->dropDownList(
        ['select'=>'select', 'number'=>'number', 'text'=>'text'],
        [
            'prompt'=>'Select Type',
            'onchange'=>'
               var t = \'{"list":[{"value":"YOUR_VALUE","label":"YOUR_LABEL"}]}\';
               if($(this).val() == "select"){
                   $("#setting-meta_attribute").val(t);
                   $(".field-setting-meta_attribute").show();
                   $("#setting-meta_attribute").show();
               }else{
                    $("#setting-meta_attribute").val(\'\');
                    $(".field-setting-meta_attribute").hide();
               }               
            '    
        ]
    ); 
    ?>

    <?= $form->field($model, 'meta_attribute')->textarea(['style' => 'display:none'])->label(false) ?>

    <?= $form->field($model, 'meta_value')->textInput(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

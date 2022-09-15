<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form mt-1">

    <?php $form = ActiveForm::begin(['action' => 'changepassword?id='.$_GET['id']]); ?>

    <div class="row">
        <div class="col s4">
            <?= $form->field($model_1, 'newpass')->passwordInput(['maxlength' => true,'value'=>""]) ?>
        </div>
        <div class="col s4">
            <?= $form->field($model_1, 'repeatnewpass')->passwordInput(['maxlength' => true,'value'=>""]) ?>
        </div>
        <div class="col s4 mt-3">
            <?= Html::submitButton(Yii::t('app', 'Update Password'), ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
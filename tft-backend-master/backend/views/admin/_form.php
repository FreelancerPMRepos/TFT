<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
$action = Yii::$app->controller->action->id;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="admin-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php if($action == 'create-admin'): ?>
        <div class="row">
            <div class="col s6">
                <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true,'value'=>""]) ?>
            </div>
            <div class="col s6">
                <?= $form->field($model, 'repeatpass')->passwordInput(['maxlength' => true]) ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group mt-2">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>



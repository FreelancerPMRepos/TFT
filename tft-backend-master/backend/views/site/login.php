<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="dialog" role="dialog" aria-labelledby="loginDialogTitle" id="loginDialog" style="display: block; height: 417px;">
        <button class="focusCycler"></button>
        <div class="dialogHeader icon leftIcon">
            <div class="dialogHeaderInner"><span class="dialogHeaderTitle" id="loginDialogTitle"></span></div>
        </div>
        <div class="dialogContent dialogContentFixed" style="top: 70px;">
            <?php $form = ActiveForm::begin(['id' => 'login-form','class'=>'login-form','options'=>['autocomplete'=>'off']]); ?>
                <div dialogsection="main" id="loginView" class="lp-grid container gutter-sm">
                    <div id="virtualkeyboard" style="display:none;">
                        <div class="simple-keyboard"></div>
                    </div>
                    <div class="small-margins">
                        <label for="loginDialogEmail" class="label first">Username</label>
                        <label class="error-summary" for="loginDialogEmail" style="display: none;">
                            <div id="validatorSummaryEmail" class="row">
                                <div class="col-1">
                                    <div class="validation-img "></div>
                                </div>
                                <div class="col-11"></div>
                            </div>
                        </label>
                    </div>

                    <div class="dropdownContainer" style="margin: 0px;">
                        <div class="relative">
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true,'class'=>'dialogInput inputCapsMatter unknown'])->label(false) ?>
                        </div>                   
                    </div>
                    <div class="row small-margins">
                        <div class="col-8">
                            <label class="label" for="loginDialogPassword">Master Password</label>
                        </div>
                    </div>
                    <div class="relative">
                        <?= $form->field($model, 'password')->passwordInput(['class'=>'dialogInput inputCapsMatter unknown'])->label(false) ?>
                    </div>
                    <div class="clear"></div>                   
                    <div class="buttons centerButtons">
                        <button id="logInButton" class="nbtn rbtn">Log In</button>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
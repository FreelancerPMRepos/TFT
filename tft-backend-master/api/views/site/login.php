<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
 <body class="login">
        <div class="container sm:px-10">
            <div class="block xl:grid grid-cols-2 gap-4">
                <!-- BEGIN: Login Info -->
                <div class="hidden xl:flex flex-col min-h-screen">
                    <div class="my-auto">
                        <img alt="Midone Tailwind HTML Admin Template" class="-intro-x w-1/2 -mt-16" src="https://midone.left4code.com/dist/images/illustration.svg">
                        <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">
                            TFT -  TRAINER PANEL
                            <br>
                            TO MANAGE CLIENTS & LICENSES
                        </div>
                        <div class="-intro-x mt-5 text-lg text-white dark:text-gray-500">ONLY TRAINER CAN LOGIN WITH THEIR ACCOUNT</div>
                    </div>
                </div>
                <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                    <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                        <div class="text-center">
                            <img src = "https://dev.targetedfitnesstraining.com/img_assets/logo.png" style="display: inline !important;" width="100">
                        </div>
                        <h2 class="mt-10 intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">
                            Sign In
                        </h2>
                        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                            <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
                            <div class="intro-x mt-8">
                                <?= $form->field($model, 'username')->textInput(['class' => 'intro-x login__input input input--lg border border-gray-300 block']) ?>
                                <div class="mt-4">
                                    <?= $form->field($model, 'password')->passwordInput(['class'=>'intro-x login__input input input--lg border border-gray-300 block']) ?>
                                </div>
                            </div>
                            <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                                <button type = "submit" class="button button--lg w-full xl:w-32 text-white bg-theme-1 xl:mr-3 align-top">Login</button>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        

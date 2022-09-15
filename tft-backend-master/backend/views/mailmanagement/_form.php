<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\MailManagement */
/* @var $form yii\widgets\ActiveForm */

$url = \yii\helpers\Url::to(['mailmanagement/user-list']);
$EmailTemplate = \common\models\EmailTemplate::find()->where(['email_slug'=>'email_template_for_admin_only'])->one();
$model->body = !empty($EmailTemplate)?$EmailTemplate['email_content']:"";
?>

<!-- <div id="theme-cutomizer-out" class="theme-cutomizer sidenav row">          -->
    <?php $form = ActiveForm::begin(['action' => ['mailmanagement/create'],'options' => ['method' => 'post']]) ?>
        <h5 class="mt-0">New Message</h5>
        <hr>
        <div class="row">
            <div class="input-field col s12">
                <?=$form->field($model, 'email')->widget(Select2::classname(), [
                    // 'data' => ['test','testww'],
                    // 'initValueText' => 'test', // set the initial display text
                    // 'theme' => Select2::THEME_DEFAULT,
                    'options' => ['placeholder' => 'Search for a Recipients ...', 'class' => 'browser-default', 'multiple' => true],
                    'pluginOptions' => [
                        // 'dropdownParent' => "#composemail",
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => 'en',
                        'multiple' => true,
                        'ajax' => [
                            'url' => $url,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'tabindex' => false,
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ]);?>
            </div>
            <div class="input-field col s12">
                <?= $form->field($model, 'email[]')->textInput(['maxlength' => true, 'placeholder' => 'Specific email address Ex : joi@gmail.com,dove@gmail.com'])->label(false); ?>
            </div>
            <div class="input-field col s12">
                <?= $form->field($model, 'subject')->textInput(['maxlength' => true, 'placeholder' => 'Subject'])->label(false); ?>
            </div>

            <div class="input-field col s12">
                <?= $form->field($model, 'body')->widget(TinyMce::className(), [
                    'options' => ['rows' => 6],
                    'language' => 'en',
                    'clientOptions' => [
                        'plugins' => [
                            "advlist autolink lists link charmap print preview anchor",
                            "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table contextmenu paste"
                        ],
                        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                    ]
                ]);?>
            </div>
        </div>
        <?= Html::submitButton('<i class="material-icons">send</i> Send', ['class' => 'btn modal-close waves-effect waves-light mr-2']) ?>
    <?php ActiveForm::end(); ?>
<!-- </div> -->

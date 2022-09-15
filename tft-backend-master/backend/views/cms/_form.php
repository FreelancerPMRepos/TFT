<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;


/* @var $this yii\web\View */
/* @var $model common\models\Cms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cms-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if(!$model->id){?>
    <?= $form->field($model, 'slug')->textInput(['rows' => 6]) ?>
    <?php } ?>
    <?= $form->field($model, 'app_body')->widget(\yii2mod\markdown\MarkdownEditor::class, [
        'editorOptions' => [
            'showIcons' => ["code", "table"],
        ],
    ]); ?>

    <?= $form->field($model, 'html_body')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advanced'
    ]);
    ?>

    <?= $form->field($model, 'meta_tile')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_keyword')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>

    <?php if(\Yii::$app->user->identity->role == 99) { ?> 
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$action = Yii::$app->controller->action->id;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sports */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sports-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'active')->dropDownList(['1' => 'Yes','0' => 'No']) ?>
        
        </div>

        <div class="form-group mt-2">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>

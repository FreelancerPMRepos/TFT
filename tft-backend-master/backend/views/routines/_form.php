<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

use common\models\Pathways;
$pathways = Pathways::find()->select(['id','CONCAT(name, " - ", subtext) as subtext'])->orderBy("subtext ASC")->asArray()->all();
$pathways = ArrayHelper::map($pathways,'id','subtext');

$action     = Yii::$app->controller->action->id;

/* @var $this yii\web\View */
/* @var $model common\models\Routines */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="routines-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'day')->dropDownList([ 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', ], ['prompt' => 'Select Day']) ?>

    <?= $form->field($model, 'pathway_id')->dropDownList($pathways,['prompt' => 'Select One']) ?>

    <?= $form->field($model, 'time_between_last_sets')->textInput() ?>

    <?php if($action == 'update'): ?>
        <div class="form-group">
            <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
        </div>
    <?php else: ?>
        <div class="form-group" style="padding-top: 2%">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    <?php endif; ?>
    <?php ActiveForm::end(); ?>

</div>

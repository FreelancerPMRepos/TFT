<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RoutinesWeeksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="routines-weeks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'routine_id') ?>

    <?= $form->field($model, 'week_no') ?>

    <?= $form->field($model, 'day') ?>

    <?= $form->field($model, 'seq_no') ?>

    <?php // echo $form->field($model, 'exe_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

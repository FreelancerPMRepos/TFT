<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RoutinesWeeksSetsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="routines-weeks-sets-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'routine_week_id') ?>

    <?= $form->field($model, 'set_no') ?>

    <?= $form->field($model, 'reps') ?>

    <?= $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'lifting_time') ?>

    <?php // echo $form->field($model, 'time_unit_countdown') ?>

    <?php // echo $form->field($model, 'coutdown_timer') ?>

    <?php // echo $form->field($model, 'time_between_set') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

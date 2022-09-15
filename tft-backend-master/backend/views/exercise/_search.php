<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ExerciseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exercise-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'exe_category_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'body_parts') ?>

    <?php // echo $form->field($model, 'steps') ?>

    <?php // echo $form->field($model, 'instructions') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'record_type') ?>

    <?php // echo $form->field($model, 'source') ?>

    <?php // echo $form->field($model, 'img') ?>

    <?php // echo $form->field($model, 'gif') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

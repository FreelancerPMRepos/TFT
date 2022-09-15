<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;

use yii\helpers\ArrayHelper;
use common\models\Exercise;

$action     = Yii::$app->controller->action->id;

/* @var $this yii\web\View */
/* @var $model common\models\RoutinesWeeksSets */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col s12 m12 l12">
      <div id="Form-advance" class="card card card-default scrollspy">
        <div class="card-content">
            <div class="routines-weeks-sets-form">
                <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="input-field col m6 s6">
                            <?= $form->field($model, 'set_no')->textInput() ?>
                        </div>
                        <?php if($action == 'sets-edit'): ?>
                            <div class="input-field col m6 s12" style="padding-top: 2%">
                            <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
                            <?= Html::a('Back', ['sets','id' => $_GET['id'],'exeID'=>$_GET['exeID'],'_id'=>$_GET['_id']], ['class'=>'btn btn-primary']) ?>
                        </div>
                        <?php else: ?>
                            <div class="input-field col m6 s12" style="padding-top: 2%">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'reps')->textInput() ?>
                        </div>
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'lifting_time')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'time_unit_countdown')->textInput() ?>
                        </div>
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'coutdown_timer')->textInput() ?>
                        </div>
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'time_between_set')->textInput() ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
            <?php if($dataProvider->totalCount > 0): ?>
            <?php //if(1): ?>
            <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [                        
                        

                        // 'id',
                        // 'routine_week_id',
                        'set_no',
                        'reps',
                        'weight',
                        'lifting_time',
                         'time_unit_countdown',
                        'coutdown_timer',
                        'time_between_set',
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{update}&nbsp{sets-delete}',
                            'header'=>'Action',
                            'buttons' => [   
                                'update'=>function ($url,$model) {
                                    return Html::a('<i class="material-icons">edit</i>', "sets-edit?id=".$_GET['id']."&exeID=".$_GET['exeID']."&c_id=".$model->id."&_id=".$_GET['_id'], ['class'=>'btn btn-lg btn-blue','data' => ['method' => 'post']]);
                                },
                                'sets-delete'=>function ($url) {
                                    return Html::a('<i class="material-icons">delete</i>', $url, ['class' => 'btn btn-lg btn-blue', 'data' => ['method' => 'post', 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?')]]);
                                },  
                            ],
                        ],
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>

            <?php Pjax::end(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>


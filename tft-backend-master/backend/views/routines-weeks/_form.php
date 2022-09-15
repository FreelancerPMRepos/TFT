<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\grid\GridView;

use yii\helpers\ArrayHelper;
use common\models\Exercise;
use common\models\ExerciseCategory;
$exe_category = ExerciseCategory::find()->select(['id','name'])->orderBy("name ASC")->asArray()->all();
$exe_category = ArrayHelper::map($exe_category,'id','name');

$exrcise_ID = Exercise::find()->select(['id','name'])->orderBy("name ASC")->asArray()->all();
$exrcise_ID = ArrayHelper::map($exrcise_ID,'id','name');


$action     = Yii::$app->controller->action->id;

/* @var $this yii\web\View */
/* @var $model common\models\RoutinesWeeks */
/* @var $form yii\widgets\ActiveForm */

// echo $dataProvider->getCount();die;
?>
<div class="row">
    <div class="col s12 m12 l12">
      <div id="Form-advance" class="card card card-default scrollspy">
        <div class="card-content">
            <div class="routines-weeks-form">
                <?php $form = ActiveForm::begin(); ?>
                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'week_no')->dropDownList([ 1 => '1', 2 => '2', 3 => '3', 4 => '4', ], ['prompt' => 'Select Week']) ?>
                        </div>
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'day')->dropDownList([ 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 6 => '6', 7 => '7', ], ['prompt' => 'Select Day']) ?>
                        </div>
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'seq_no')->textInput() ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'exe_category_id')->dropDownList($exe_category,['prompt' => 'Select Body Part','id'=>'exe_category_id']) ?>
                        </div>
                        <div class="input-field col m4 s12">
                            <?= $form->field($model, 'exe_id')->dropDownList($exrcise_ID,['prompt' => 'Select Exercise Name']) ?>
                        </div>
                        <?php if($action == 'mapping-edit'): ?>
                            <div class="input-field col m4 s12" style="padding-top: 2%">
                                <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
                                <?= Html::a('Back', ['mapping','id' => $_GET['id']], ['class'=>'btn btn-primary']) ?>
                            </div>
                        <?php else: ?>
                            <div class="input-field col m4 s12" style="padding-top: 2%">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
            <?php if($dataProvider->totalCount > 0): ?>
            <?php //if(1): ?>
                <div class="section">

                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        // 'summary'=> false,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'exe_id',
                                'value' => function($model){
                                    $exrcise_ID = Exercise::find()->select(['name'])->where(['id'=>$model->exe_id])->asArray()->one();
                                    return $exrcise_ID['name'];
                                }
                            ],
                            'week_no',
                            'day',
                            'seq_no',
                            
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{create}&nbsp{update}&nbsp{mapping-delete}',
                                'header'=>'Action',
                                'buttons' => [   
                                    'create'=>function ($url,$model) {
                                        return Html::a('SETS', "sets?id=".$model->id."&exeID=".$model->exe_id."&_id=".$_GET['id'], ['class'=>'btn btn-lg btn-blue','data' => ['method' => 'post']]);
                                    },  
                                    'update'=>function ($url,$model) {
                                        return Html::a('<i class="material-icons">edit</i>', "mapping-edit?id=".$_GET['id']."&sets_id=".$model->id, ['class'=>'btn btn-lg btn-blue','data' => ['method' => 'post']]);
                                    },
                                    'mapping-delete'=>function ($url) {
                                        return Html::a('<i class="material-icons">delete</i>', $url, ['class' => 'btn btn-lg btn-blue', 'data' => ['method' => 'post', 'confirm' => Yii::t('app', 'Are you sure you want to delete this item?')]]);
                                    },   
                                ],
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
                <?php endif; ?>
        </div>
    </div>
</div>

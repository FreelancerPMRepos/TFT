<?php
$action = Yii::$app->controller->action->id;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
$ExerciseCategory = ArrayHelper::map(\common\models\ExerciseCategory::find()->orderBy('name ASC')->where(1)->asArray()->all(),'id','name');;
/* @var $this yii\web\View */
/* @var $model common\models\Exercise */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="exercise-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col s2 mt-1">
            <?= $form->field($model, 'name')->textInput() ?>
        </div>
        <div class="col s2 mt-1">
            <?= $form->field($model, 'exe_category_id')
            ->dropDownList($ExerciseCategory,['prompt'=>'Select One']) ?>
        </div>
       
        <div class="col s2 mt-1">
            <?= $form->field($model, 'type')->dropDownList([ 'Pull' => 'Pull', 'Push' => 'Push', 'Pull / Push' => 'Pull / Push', ]) ?>
        </div>
        <div class="col s2 mt-1">
            <?= $form->field($model, 'record_type')->dropDownList([ 'Weight And Reps' => 'Weight And Reps', 'Reps Only' => 'Reps Only', 'Cardio' => 'Cardio', 'Time Only' => 'Time Only', 'Reps and Interval/Duration' => 'Reps and Interval/Duration', ], ['prompt' => 'Select One']) ?>
        </div>
        <div class="col s2 mt-1">
             <?= $form->field($model, 'source')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col s2 mt-1">
            <?= $form->field($model, 'is_active')->dropDownList(['1' => 'Yes','0' => 'No']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col s3 mt-1">
            <?= $form->field($model, 'description')->textarea(['rows' => 6])->widget(\yii2mod\markdown\MarkdownEditor::class, [
                'editorOptions' => [
                    'showIcons' => ["code", "table"],
                ],
            ]) ?>
        </div>
        <div class="col s3 mt-1">
            <?= $form->field($model, 'body_parts')->textarea(['rows' => 6])->widget(\yii2mod\markdown\MarkdownEditor::class, [
                'editorOptions' => [
                    'showIcons' => ["code", "table"],
                ],
            ]); ?>
        </div>
        <div class="col s3 mt-1">
            <?= $form->field($model, 'steps')->textarea(['rows' => 6])->widget(\yii2mod\markdown\MarkdownEditor::class, [
            'editorOptions' => [
                'showIcons' => ["code", "table"],
            ],
            ]) ?>
        </div>
        <div class="col s3 mt-1">
            <?= $form->field($model, 'instructions')->textarea(['rows' => 6])->widget(\yii2mod\markdown\MarkdownEditor::class, [
                    'editorOptions' => [
                        'showIcons' => ["code", "table"],
                    ],
                ]) ?>
        </div>
    </div>
     
    <div class="row">        
        <div class="col s3">
            <?= $form->field($model, 'image')->fileInput() ?>
            <?php if($action == 'update'): ?>
                <div class="mt-2">
                <?php
                    $default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                    if(!empty($model->img)){
                        $basePath =  Yii::getAlias('@webroot/../../img_assets/exercise/'.$model->img);
                        if(file_exists($basePath)){
                            echo '<img src="' .Yii::$app->request->baseUrl."/../img_assets/exercise/".$model->img. "?r=".rand() . '" width="200" alt="">';
                        }else{
                            echo '<img src="' . $default_Image . '" width="200" alt="">';
                        }
                    }else{
                        echo '<img src="' . $default_Image . '" width="200" alt="">';
                    }
                ?>
                </div>
            <?php endif; ?>

        </div>
        <div class="col s3">
            <?= $form->field($model, 'GIF')->fileInput()->label('Image 2'); ?>
            <?php if($action == 'update'): ?>
                <div class="mt-2">
                    <?php
                        $default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                        if(!empty($model->gif)){
                            $basePath =  Yii::getAlias('@webroot/../../img_assets/exercise/'.$model->gif);
                            if(file_exists($basePath)){
                                echo '<img src="' .Yii::$app->request->baseUrl."/../img_assets/exercise/".$model->gif. "?r=".rand() . '" width="200" alt="">';
                            }else{
                                echo '<img src="' . $default_Image . '" width="200" alt="">';
                            }
                        }else{
                            echo '<img src="' . $default_Image . '" width="200" alt="">';
                        }
                    ?>
                </div>
            <?php endif; ?>
        </div>       
    </div>  
    <div class="form-group mt-2">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

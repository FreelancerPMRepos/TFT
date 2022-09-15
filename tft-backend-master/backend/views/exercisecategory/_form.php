<?php
$action = Yii::$app->controller->action->id;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ExerciseCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exercise-category-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="mt-2 mb-2">
        <?php if($action == 'update'): ?>
            <div class="mt-2">
                <?php
                    $default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                    if(!empty($model->img)){
                        $basePath =  Yii::getAlias('@webroot/../../img_assets/gym/'.$model->img);
                        if(file_exists($basePath)){
                            echo '<img src="' .Yii::$app->request->baseUrl."/../img_assets/gym/".$model->img. "?r=".rand() . '" width="200" alt="">';
                        }else{
                            echo '<img src="' . $default_Image . '" width="200" alt="">';
                        }
                    }else{
                        echo '<img src="' . $default_Image . '" width="200" alt="">';
                    }
                ?>
            </div>
        <?php endif; ?>
        <?= $form->field($model, 'img')->fileInput() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

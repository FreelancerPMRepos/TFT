<?php
$action = Yii::$app->controller->action->id;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col s4 center">
            <p>User Image</p>
            <?php
                $default_Image = Yii::$app->request->baseUrl."/../img_assets/upload/nophotoavailable.jpg";
                if(!empty($model_1->photo)){
                    $basePath =  Yii::getAlias('@webroot/../../img_assets/users/'.$model_1->photo);
                    if(file_exists($basePath)){
                        echo '<img src="' .Yii::$app->request->baseUrl."/../img_assets/users/".$model_1->photo. "?r=".rand() . '" width="200" alt="">';
                    }else{
                        echo '<img src="' . $default_Image . '" width="200" alt="">';
                    }
                }else{
                    echo '<img src="' . $default_Image . '" width="200" alt="">';
                }
            ?>
            <?= $form->field($model_1, 'img')->fileInput() ?>
        </div>
        <div class="col s8">
            <div class="row">
                <div class="col s6">
                    <?= $form->field($model_1, 'date_of_birth')->textInput(['maxlength' => true,'placeholder'=>"YY / MM / DD"]) ?>
                    <div class="row">
                        <div class="col s8">
                            <?= $form->field($model_1, 'weight')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col s4">
                            <?= $form->field($model_1, 'weight_unit')->dropDownList([ 'lb' => 'Lb', 'kg' => 'Kg', ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col s6">
                    <?= $form->field($model_1, 'gender')->dropDownList(['male' => 'Male', 'female' => 'Female','other'=> 'Other']); ?>
                    <div class="row mt-3">
                        <div class="col s8">
                            <?= $form->field($model_1, 'height')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col s4">
                            <?= $form->field($model_1, 'height_unit')->dropDownList([ 'in' => 'In', 'cm' => 'Cm', ]) ?>
                        </div>
                    </div>           
                </div>
            </div>
            <div class="row">
                <div class="col s12">                
                    <?= $form->field($model_1, 'units_of_measurement')->dropDownList(['lbs/in' => 'Lbs / In', 'kg/cm' => 'Kg / Cm']); ?>
                </div>
            </div>
        </div>
        <!-- admin::model -->
        <div class="col s12">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <div class="row">
                <?php if($action == "create"): ?>
                    <div class="col s6">
                        <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true,'value'=>""]) ?>
                    </div>
                    <div class="col s6">
                        <?= $form->field($model, 'repeatpass')->passwordInput(['maxlength' => true,'value'=>""]) ?> 
                    </div>
                <?php endif; ?>
            </div>
            <?php if($action == "update"): ?>
                <div class="form-group mt-1">
                    <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
                </div>   
            <?php else: ?>
                <div class="form-group mt-1">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                </div>
            <?php endif; ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php if($action == "update"): ?>
    <?= $this->render('_password', [
        'model_2' => $model_2,
    ]) ?> 
<?php endif; ?>

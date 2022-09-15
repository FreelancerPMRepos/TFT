<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

use common\models\Sports;
use yii\helpers\ArrayHelper;
$sports = Sports::find()->select(['id','name'])->where(['active'=>1])->orderBy("name ASC")->asArray()->all();
$sportsID = ArrayHelper::map($sports,'id','name');

?>
<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="input-field col m6 s12">
            <?= $form->field($model, 'user_selected_sport_id')->dropDownList($sportsID, ['prompt' => 'Select Sports']) ?>
        </div>
        <div class="input-field col m6 s12">
            <?= $form->field($model, 'how_many_day_per_week')->dropDownList(['2'=>'2','3'=>'3','4'=>'4','5'=>'5'], ['prompt' => 'Select Day']) ?>
        </div>
    </div>
    <div class="row">
        <div id="div1">
        </div>

        <div id="div2">
        </div>

        <div id="div3">
        </div>

        <div id="div4">
        </div>

        <div id="div5">
        </div>
    </div>
    <div class="input-field">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
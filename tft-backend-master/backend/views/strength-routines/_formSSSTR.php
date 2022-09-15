<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Pjax;

use common\models\Sports;
use yii\helpers\ArrayHelper;
$sports = Sports::find()->select(['id','name'])->where(['active_for_ssstr'=>1])->orderBy("name ASC")->asArray()->all();
$sportsID = ArrayHelper::map($sports,'id','name');

?>
<?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="input-field col m6 s12">
            <?= $form->field($model, 'user_selected_sport_id')->dropDownList($sportsID, ['prompt' => 'Select Sports']) ?>
        </div>
        <div class="input-field col m6 s12">
            <?= $form->field($model, 'user_selected_season')->dropDownList([ 'In' => 'ln Season', 'Pre' => 'Pre Season' , 'Off' => 'Off Season' ], ['prompt' => "Select Sport's Season"]) ?>
        </div>
    </div>
    <div class="input-field">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end(); ?>
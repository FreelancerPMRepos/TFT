<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MailManagementSearch */
/* @var $form yii\widgets\ActiveForm */
$currentMailType = 'inbox';
if(!empty($_GET['MailManagementSearch']['email_type'])){
    $currentMailType = $_GET['MailManagementSearch']['email_type'];
}
?>
<?php $form = ActiveForm::begin([
    'action' => ['index','MailManagementSearch[email_type]' => $currentMailType],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1
    ],
]); ?>
    <i class="material-icons mr-2 search-icon">search</i>
    <?= $form->field($model, 'name')->textInput(['class' => 'app-filter', 'placeholder' => 'Search Mail'])->label(false); ?>
    <?php ActiveForm::end(); ?>
</div>

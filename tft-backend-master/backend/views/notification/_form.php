<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$appCountries   =  \yii\helpers\ArrayHelper::map(\common\models\AppsCountries::find()->where(1)->asArray()->all(),'id','country_name');

?>

<div class="row">
    <div class="col m4">
        <?php $form = ActiveForm::begin(); ?>            
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'message')->textInput() ?>  
        <div class="form-group">
            <input type="hidden" id="notification-users" class="form-control" name="users">
            <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col m8">
    <?php Pjax::begin(); ?>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions'=>['class'=>'filled-in'],
                     'header'=>'<label><input type="checkbox" class="select-on-check-all filled-in" name="selection_all" value="1"><span></span></label>',
                    'content'=>function($model) {
                         return '<label>
                                        <input type="checkbox" class="filled-in child" name="selection[]" value="'.$model['user_id'].'">
                                        <span></span>
                                </label>';
                     }
                    // you may configure additional properties here
                ],                
                [
                    'attribute' => 'full_name',
                    'format' => 'html', 
                    'value' => function ($model) {
                        return '<div class="col s9">
                            <a href="#">
                                <p class="m-0">'.$model['user']['username'].'</p>
                            </a>
                            <p class="m-0 grey-text lighten-3">'.$model['user']['email'].'</p>
                           
                        </div>';
                    },
                ],
               
              
            ],
        ]); ?>
    <?php Pjax::end(); ?>             
    </div>
        
      
       
    
</div>
<script>
$( document ).ready(function() {
    $(document).on('click','.filled-in',function(){
        setTimeout(() => {
            var array = []
            var checkboxes = document.querySelectorAll('.child:checked')
            for (var i = 0; i < checkboxes.length; i++) {
                array.push(checkboxes[i].value)
            }
            var x = array.toString();
            console.log(array);
            $('#notification-users').val(x); 
        },1000);
             
    });  
});
</script>
